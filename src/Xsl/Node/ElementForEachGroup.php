<?php
namespace Genkgo\Xsl\Xsl\Node;

use DOMDocument;
use DOMElement;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xpath\FunctionBuilder;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;
use Genkgo\Xsl\Xsl\XslTransformations;

/**
 * Class ElementForEachGroup
 * @package Genkgo\Xsl\Xsl\Node
 */
class ElementForEachGroup implements ElementTransformerInterface
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
        return $element->ownerDocument->documentElement->getAttribute('version') !== '1.0' && $element->localName === 'for-each-group';
    }

    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element)
    {
        $select = $element->getAttribute('select');
        $groupBy = $element->getAttribute('group-by');

        $callback = (new FunctionBuilder('php:function'))
            ->addArgument(PhpCallback::class . '::call')
            ->addArgument(XslTransformations::URI . ':group-by')
            ->addExpression($select)
            ->addArgument(base64_encode($groupBy))
        ;

        $xslForEach = $element->ownerDocument->createElementNS(XslTransformations::URI, 'xsl:for-each');
        $xslForEach->setAttribute('select', $callback->build() . '/xs:sequence/*');

        while ($element->childNodes->length > 0) {
            $xslForEach->appendChild($element->childNodes->item(0));
        }

        $element->parentNode->insertBefore($xslForEach, $element);
        $element->parentNode->removeChild($element);
    }
}
