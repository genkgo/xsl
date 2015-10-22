<?php
namespace Genkgo\Xsl\Xsl\Node;

use DOMElement;
use Genkgo\Xsl\Util\FetchNamespacesFromDocument;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;

/**
 * Class AttributeSelect
 * @package Genkgo\Xsl\Xsl\Element
 */
final class AttributeSelect implements ElementTransformerInterface
{
    /**
     * @var Compiler
     */
    private $xpathCompiler;

    /**
     * @param Compiler $compiler
     */
    public function __construct(Compiler $compiler)
    {
        $this->xpathCompiler = $compiler;
    }

    /**
     * @param DOMElement $element
     * @return bool
     */
    public function supports(DOMElement $element)
    {
        return $element->hasAttribute('select');
    }

    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element)
    {
        $element->setAttribute(
            'select',
            $this->xpathCompiler->compile(
                $element->getAttribute('select'),
                FetchNamespacesFromDocument::fetch($element->ownerDocument)
            )
        );
    }
}
