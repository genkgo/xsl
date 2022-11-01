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
use Genkgo\Xsl\Xsl\Functions\GroupBy;
use Genkgo\Xsl\Xsl\XslTransformations;

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

        $xslForEach = $this->createForEachStatement($element, $groupId);
        $groupingKeyVariable = $this->createGroupingKeyVariable($element, $groupId);
        $unGroupedVariable = $this->createUnGroupedVariable($element, $groupId);
        $groupVariable = $this->createGroupVariable($element, $groupId);

        $childSorts = new \DOMXPath($document);
        $childSorts->registerNamespace('xsl', XslTransformations::URI);
        $sorts = $childSorts->query('xsl:sort', $element);
        foreach ($sorts as $item) {
            if ($item instanceof DOMElement) {
                $xslForEach->appendChild($this->convertSort($element, $item, $groupId));
            }
        }

        $xslForEach->appendChild($groupingKeyVariable);
        $xslForEach->appendChild($groupVariable);

        while ($element->childNodes->length > 0) {
            $item = $element->childNodes->item(0);
            if ($item !== null) {
                $xslForEach->appendChild($item);
            }
        }

        $element->parentNode->insertBefore($xslForEach, $element);
        $element->parentNode->insertBefore($unGroupedVariable, $xslForEach);
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

        $callback = (new FunctionBuilder('php:function'))
            ->addArgument(PhpCallback::class . '::callStatic')
            ->addArgument(GroupBy::class)
            ->addArgument('distinct')
            ->addExpression($select)
            ->addArgument(\base64_encode($groupBy))
            ->addArgument(\base64_encode((string)\json_encode($namespaces)))
            ->addExpression('.')
            ->addExpression('position()');

        $xslForEach = $element->ownerDocument->createElementNS(XslTransformations::URI, 'xsl:for-each');
        $xslForEach->setAttribute('select', $callback->build() . '/xs:sequence/*');
        $xslForEach->setAttribute('group-id', $groupId);

        return $xslForEach;
    }

    /**
     * @param DOMElement $element
     * @param string $groupId
     * @return DOMElement
     */
    private function createGroupVariable(DOMElement $element, string $groupId): DOMElement
    {
        $variable = $element->ownerDocument->createElementNS(XslTransformations::URI, 'xsl:variable');
        $variable->setAttribute('name', 'current-group-' . $groupId);
        $variable->setAttribute(
            'select',
            \sprintf(
                '$current-un-grouped-%s[current() = string(%s)]',
                $groupId,
                $this->xpathCompiler->compile($element->getAttribute('group-by'), $element)
            )
        );
        return $variable;
    }

    /**
     * @param DOMElement $element
     * @param string $groupId
     * @return DOMElement
     */
    private function createGroupingKeyVariable(DOMElement $element, string $groupId): DOMElement
    {
        $variable = $element->ownerDocument->createElementNS(XslTransformations::URI, 'xsl:variable');
        $variable->setAttribute('name', 'current-grouping-key-' . $groupId);
        $variable->setAttribute('select', 'current()');
        return $variable;
    }

    /**
     * @param DOMElement $element
     * @param string $groupId
     * @return DOMElement
     */
    private function createUnGroupedVariable(DOMElement $element, string $groupId): DOMElement
    {
        $variable = $element->ownerDocument->createElementNS(XslTransformations::URI, 'xsl:variable');
        $variable->setAttribute('name', 'current-un-grouped-' . $groupId);
        $variable->setAttribute('select', $this->xpathCompiler->compile($element->getAttribute('select'), $element));
        return $variable;
    }

    /**
     * @param DOMElement $sort
     * @param string $groupId
     * @return DOMElement
     */
    private function convertSort(DOMElement $element, DOMElement $sort, string $groupId)
    {
        $sort->setAttribute(
            'select',
            \str_replace(
                'current-group()',
                \sprintf(
                    '$current-un-grouped-%s[current() = string(%s)]',
                    $groupId,
                    $this->xpathCompiler->compile($element->getAttribute('group-by'), $element)
                ),
                $sort->getAttribute('select')
            )
        );
        return $sort;
    }
}
