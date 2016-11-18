<?php
namespace Genkgo\Xsl\Xsl\Node;

use DOMDocument;
use DOMElement;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\Util\FetchNamespacesFromNode;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xpath\FunctionBuilder;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;
use Genkgo\Xsl\Xsl\XslTransformations;

/**
 * Class ElementForEachGroup
 * @package Genkgo\Xsl\Xsl\Node
 */
final class ElementForEachGroup implements ElementTransformerInterface
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
        return (
            $element->ownerDocument->documentElement->getAttribute('version') !== '1.0' &&
            $element->localName === 'for-each-group'
        );
    }

    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element)
    {
        $groupId = md5(uniqid());
        $document = $element->ownerDocument;
        $select = $element->getAttribute('select');

        $xslForEach = $this->createForEachStatement($element, $groupId);
        $unGroupedVariable = $this->createUnGroupedVariable($document, $groupId, $select);
        $groupVariable = $this->createGroupVariable($document, $groupId);

        $sorts = $element->getElementsByTagNameNS(XslTransformations::URI, 'sort');
        foreach ($sorts as $sort) {
            $xslForEach->appendChild($this->convertSort($sort, $groupId));
        }

        $xslForEach->appendChild($groupVariable);

        while ($element->childNodes->length > 0) {
            $xslForEach->appendChild($element->childNodes->item(0));
        }

        $element->parentNode->insertBefore($xslForEach, $element);
        $element->parentNode->insertBefore($unGroupedVariable, $xslForEach);
        $element->parentNode->removeChild($element);
    }

    /**
     * @param DOMElement $element
     * @param $groupId
     * @return DOMElement
     */
    private function createForEachStatement(DOMElement $element, $groupId)
    {
        $select = $element->getAttribute('select');
        $groupBy = $element->getAttribute('group-by');
        $namespaces = FetchNamespacesFromNode::fetch($element);

        $addGroupingItem = (new FunctionBuilder('php:function'))
            ->addArgument(PhpCallback::class . '::call')
            ->addArgument(XslTransformations::URI . ':group-by')
            ->addArgument($groupId)
            ->addExpression('.')
            ->addExpression('generate-id(.)')
            ->addArgument(base64_encode($groupBy))
            ->addArgument(base64_encode(json_encode($namespaces)))
        ;

        $updatedSelect = $select.'[' . $addGroupingItem->build() . ']';

        $createGroupFunction = (new FunctionBuilder('php:function'))
            ->addArgument(PhpCallback::class . '::call')
            ->addArgument(XslTransformations::URI . ':group-iterate')
            ->addArgument($groupId)
            ->addExpression($updatedSelect)
        ;

        $xslForEach = $element->ownerDocument->createElementNS(XslTransformations::URI, 'xsl:for-each');
        $xslForEach->setAttribute('select', $createGroupFunction->build() . '//xsl:group');
        $xslForEach->setAttribute('group-id', $groupId);

        return $xslForEach;
    }

    /**
     * @param DOMDocument $document
     * @param $groupId
     * @param $select
     * @return DOMElement
     */
    private function createUnGroupedVariable(DOMDocument $document, $groupId, $select)
    {
        $variable = $document->createElementNS(XslTransformations::URI, 'xsl:variable');
        $variable->setAttribute('name', 'current-un-grouped-' . $groupId);
        $variable->setAttribute('select', $select);
        return $variable;
    }

    /**
     * @param DOMDocument $document
     * @param $groupId
     * @return DOMElement
     */
    private function createGroupVariable(DOMDocument $document, $groupId)
    {
        $variable = $document->createElementNS(XslTransformations::URI, 'xsl:variable');
        $variable->setAttribute('name', 'current-group-' . $groupId);
        $variable->setAttribute('select', '.');
        return $variable;
    }

    private function convertSort(DOMElement $sort, $groupId)
    {
        $sort->setAttribute(
            'select',
            str_replace(
                'current-group()',
                '$current-un-grouped-' . $groupId . '[generate-id(.) = current()//xsl:element-id]',
                $sort->getAttribute('select')
            )
        );
        return $sort;
    }
}
