<?php
namespace Genkgo\Xsl\Xsl\Node;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Genkgo\Xsl\Callback\PhpCallback;
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

        $xslForEach->appendChild($groupVariable);

        while ($element->childNodes->length > 0) {
            $xslForEach->appendChild($element->childNodes->item(0));
        }

        $element->parentNode->insertBefore($xslForEach, $element);
        $element->parentNode->insertBefore($unGroupedVariable, $xslForEach);
        $element->parentNode->removeChild($element);

        $this->replaceCurrentGroup($xslForEach, $groupId);
    }

    /**
     * @param DOMElement $element
     * @param $groupId
     * @return DOMElement
     */
    private function createForEachStatement (DOMElement $element, $groupId) {
        $select = $element->getAttribute('select');
        $groupBy = $element->getAttribute('group-by');

        $addGroupingItem = (new FunctionBuilder('php:function'))
            ->addArgument(PhpCallback::class . '::call')
            ->addArgument(XslTransformations::URI . ':group-by')
            ->addArgument($groupId)
            ->addExpression('.')
            ->addExpression('generate-id(.)')
            ->addArgument(base64_encode($groupBy))
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
        return $xslForEach;
    }

    /**
     * @param DOMDocument $document
     * @param $groupId
     * @param $select
     * @return DOMElement
     */
    private function createUnGroupedVariable (DOMDocument $document, $groupId, $select) {
        $variable = $document->createElementNS(XslTransformations::URI, 'xsl:variable');
        $variable->setAttribute('name', 'current-un-grouped-' . $groupId);
        $variable->setAttribute('select', $select);
        return $variable;
    }

    /**
     * @param DOMElement $xslForEach
     * @param $groupId
     */
    private function replaceCurrentGroup (DOMElement $xslForEach, $groupId) {
        $document = $xslForEach->ownerDocument;

        $findCurrentGroup = new DOMXPath($document);
        $findCurrentGroup->registerNamespace('xsl', XslTransformations::URI);

        /** @var DOMNodeList|DOMElement[] $currentGroupElements */
        $currentGroupElements = $findCurrentGroup->query(
            './/xsl:*[contains(@select, "current-group()") or contains(@test, "current-group()") or contains(@select, "current-grouping-key()") or contains(@test, "current-grouping-key()")]',
            $xslForEach
        );

        $searchReplace = [
            ['current-group()', 'current-grouping-key()'],
            ['current-group("' . $groupId . '")','current-grouping-key("' . $groupId . '")']
        ];

        foreach ($currentGroupElements as $currentGroupElement) {
            if ($currentGroupElement->hasAttribute('select')) {
                $select = str_replace($searchReplace[0], $searchReplace[1], $currentGroupElement->getAttribute('select'));
                $currentGroupElement->setAttribute('select', $select);
            }

            if ($currentGroupElement->hasAttribute('test')) {
                $test = str_replace($searchReplace[0], $searchReplace[1], $currentGroupElement->getAttribute('test'));
                $currentGroupElement->setAttribute('test', $test);
            }
        }
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
}
