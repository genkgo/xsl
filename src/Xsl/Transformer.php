<?php
namespace Genkgo\Xsl\Xsl;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use Genkgo\Xsl\TransformerInterface;
use Genkgo\Xsl\Transpiler;
use Genkgo\Xsl\Xpath\Compiler;

/**
 * Class Transformer
 * @package Genkgo\Xsl\Xsl
 */
class Transformer implements TransformerInterface
{
    /**
     * @var Compiler
     */
    private $xpathCompiler;

    /**
     * Transformer constructor.
     * @param Compiler $xpathCompiler
     */
    public function __construct(Compiler $xpathCompiler)
    {
        $this->xpathCompiler = $xpathCompiler;
    }


    /**
     * @param DOMDocument $document
     */
    public function transform(DOMDocument $document)
    {
        $document->documentElement->setAttribute('xmlns:php', 'http://php.net/xsl');
        $matchAndSelectElements = new DOMXPath($document);

        /** @var DOMNodeList|DOMElement[] $list */
        $list = $matchAndSelectElements->query('//xsl:*[@match|@select]');
        foreach ($list as $element) {
            if ($element->hasAttribute('match')) {
                $element->setAttribute(
                    'match',
                    $this->xpathCompiler->compile(
                        $element->getAttribute('match')
                    )
                );
            }

            if ($element->hasAttribute('select')) {
                $element->setAttribute(
                    'select',
                    $this->xpathCompiler->compile(
                        $element->getAttribute('select')
                    )
                );
            }

            if ($element->nodeName === 'xsl:value-of' && $element->hasAttribute('separator')) {
                $select = $element->getAttribute('select');
                $separator = $element->getAttribute('separator');
                $callback = Elements::class . '::valueOfSeparate';

                $element->setAttribute(
                    'select',
                    'php:function(\'' . $callback . '\', ' . $select . ', \'' . $separator . '\')'
                );
            }
        }
    }
}
