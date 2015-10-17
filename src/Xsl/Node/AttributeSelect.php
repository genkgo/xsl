<?php
namespace Genkgo\Xsl\Xsl\Node;

use DOMElement;
use Genkgo\Xsl\DocumentContext;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;

/**
 * Class AttributeSelect
 * @package Genkgo\Xsl\Xsl\Element
 */
class AttributeSelect implements ElementTransformerInterface {

    /**
     * @var Compiler
     */
    private $xpathCompiler;

    /**
     * @param Compiler $compiler
     */
    public function __construct (Compiler $compiler) {
        $this->xpathCompiler = $compiler;
    }

    /**
     * @param DOMElement $element
     * @param DocumentContext $context
     */
    public function transform(DOMElement $element, DocumentContext $context)
    {
        if ($element->hasAttribute('select')) {
            $element->setAttribute(
                'select',
                $this->xpathCompiler->compile(
                    $element->getAttribute('select'),
                    $context
                )
            );
        }
    }

}