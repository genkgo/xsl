<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Node;

use DOMDocument;
use DOMElement;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\Util\FetchNamespacesFromNode;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xpath\FunctionBuilder;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;
use Genkgo\Xsl\Xsl\XslTransformations;

final class ElementForEachGroup implements ElementTransformerInterface
{
    /**
     * @param DOMElement $element
     * @return bool
     */
    public function supports(DOMElement $element): bool
    {
        return (
            $element->ownerDocument->documentElement->getAttribute('version') !== '1.0' &&
            $element->localName === 'for-each-group'
        );
    }

    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element): void
    {
        $groupId = \md5(\uniqid());
        $document = $element->ownerDocument;
        $select = $element->getAttribute('select');

        $xslForEach = $this->createForEachStatement($element, $groupId);
        $unGroupedVariable = $this->createUnGroupedVariable($document, $groupId, $select);
        $iterationId = $this->createIterationId($document, $groupId);
        $groupVariable = $this->createGroupVariable($document, $groupId);

        $childSorts = new \DOMXPath($document);
        $childSorts->registerNamespace('xsl', XslTransformations::URI);
        $sorts = $childSorts->query('xsl:sort', $element);
        foreach ($sorts as $item) {
            if ($item instanceof DOMElement) {
                $xslForEach->appendChild($this->convertSort($item, $groupId));
            }
        }

        $xslForEach->appendChild($groupVariable);

        while ($element->childNodes->length > 0) {
            $item = $element->childNodes->item(0);
            if ($item !== null) {
                $xslForEach->appendChild($item);
            }
        }

        $element->parentNode->insertBefore($xslForEach, $element);
        $element->parentNode->insertBefore($unGroupedVariable, $xslForEach);
        $element->parentNode->insertBefore($iterationId, $unGroupedVariable);
        $element->parentNode->removeChild($element);
    }

    /**
     * @param DOMElement $element
     * @param string $groupId
     * @return DOMElement
     */
    private function createForEachStatement(DOMElement $element, string $groupId): DOMElement
    {
        $select = $element->getAttribute('select');
        $groupBy = $element->getAttribute('group-by');
        $namespaces = FetchNamespacesFromNode::fetch($element);

        $addGroupingItem = (new FunctionBuilder('php:function'))
            ->addArgument(PhpCallback::class . '::call')
            ->addArgument(XslTransformations::URI . ':group-by')
            ->addArgument($groupId)
            ->addExpression('$iteration-' . $groupId)
            ->addExpression('.')
            ->addExpression('generate-id(.)')
            ->addArgument(\base64_encode($groupBy))
            ->addArgument(\base64_encode((string)\json_encode($namespaces)));

        $updatedSelect = $select.'[' . $addGroupingItem->build() . ']';

        $createGroupFunction = (new FunctionBuilder('php:function'))
            ->addArgument(PhpCallback::class . '::call')
            ->addArgument(XslTransformations::URI . ':group-iterate')
            ->addArgument($groupId)
            ->addExpression('$iteration-' . $groupId)
            ->addExpression($updatedSelect)
        ;

        $xslForEach = $element->ownerDocument->createElementNS(XslTransformations::URI, 'xsl:for-each');
        $xslForEach->setAttribute('select', $createGroupFunction->build() . '//xsl:group');
        $xslForEach->setAttribute('group-id', $groupId);

        return $xslForEach;
    }

    /**
     * @param DOMDocument $document
     * @param string $groupId
     * @param string $select
     * @return DOMElement
     */
    private function createUnGroupedVariable(DOMDocument $document, string $groupId, string $select): DOMElement
    {
        $variable = $document->createElementNS(XslTransformations::URI, 'xsl:variable');
        $variable->setAttribute('name', 'current-un-grouped-' . $groupId);
        $variable->setAttribute('select', $select);
        return $variable;
    }

    /**
     * @param DOMDocument $document
     * @param string $groupId
     * @return DOMElement
     */
    private function createIterationId(DOMDocument $document, string $groupId): DOMElement
    {
        $createIterationId = (new FunctionBuilder('php:function'))
            ->addArgument(PhpCallback::class . '::call')
            ->addArgument(XslTransformations::URI . ':group-iteration-id')
            ->addArgument($groupId);

        $variable = $document->createElementNS(XslTransformations::URI, 'xsl:variable');
        $variable->setAttribute('name', 'iteration-' . $groupId);
        $variable->setAttribute('select', $createIterationId->build());
        return $variable;
    }

    /**
     * @param DOMDocument $document
     * @param string $groupId
     * @return DOMElement
     */
    private function createGroupVariable(DOMDocument $document, string $groupId): DOMElement
    {
        $variable = $document->createElementNS(XslTransformations::URI, 'xsl:variable');
        $variable->setAttribute('name', 'current-group-' . $groupId);
        $variable->setAttribute('select', '.');
        return $variable;
    }

    /**
     * @param DOMElement $sort
     * @param string $groupId
     * @return DOMElement
     */
    private function convertSort(DOMElement $sort, string $groupId)
    {
        $sort->setAttribute(
            'select',
            \str_replace(
                'current-group()',
                '$current-un-grouped-' . $groupId . '[generate-id(.) = current()//xsl:element-id]',
                $sort->getAttribute('select')
            )
        );
        return $sort;
    }
}
