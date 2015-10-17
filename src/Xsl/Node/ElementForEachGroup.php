<?php
namespace Genkgo\Xsl\Xsl\Node;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\DocumentContext;
use Genkgo\Xsl\Schema\XsSequence;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Xpath\FunctionBuilder;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;
use Genkgo\Xsl\Xsl\Transformer;
use Genkgo\Xsl\Xsl\XslTransformations;

class ElementForEachGroup implements ElementTransformerInterface {

    public function transform(DOMElement $element, DocumentContext $context)
    {
        if ($element->nodeName === 'xsl:for-each-group') {
            $select = $element->getAttribute('select');
            $groupBy = $element->getAttribute('group-by');

            $callback = (new FunctionBuilder('php:function'))
                ->addArgument(PhpCallback::class . '::callContext')
                ->addArgument(spl_object_hash($context->getTransformationContext()))
                ->addArgument(static::class)
                ->addArgument('groupBy')
                ->addArgument($select, false)
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

    /**
     * @param TransformationContext $context
     * @param DOMDocument[] $elements
     * @param $groupBy
     * @return string
     */
    public static function groupBy(TransformationContext $context, $elements, $groupBy)
    {
        if (count($elements) === 0) {
            return new XsSequence();
        }

        $groupBy = base64_decode($groupBy);
        $groups = [];
        $currentGroup = [];

        $documentContext = new DocumentContext($context);
        $documentContext->setNamespaces(Transformer::retrieveNamespacesFromDocument($elements[0]->ownerDocument));

        foreach ($elements as $key => $element) {
            $xpath = new DOMXPath($element->ownerDocument);
            $xpath->registerPhpFunctions($context->getPhpFunctions());

            $groupingKey = $xpath->evaluate(
                $context->getXpathCompiler()->compile('string(' . $groupBy . ')', $documentContext),
                $element
            );

            $groups[$groupingKey][] = $element;
            $currentGroup[spl_object_hash($element)] = $groupingKey;
        }

        return XsSequence::fromArray(

            array_map(function ($group) {
                return $group[0];
            }, $groups),

            function (DOMElement $item, DOMElement $originalElement) use ($context, $groups, $currentGroup) {
                $groupingKey = $currentGroup[spl_object_hash($originalElement)];
                $context->setElementContext($item, [$groupingKey, $groups[$groupingKey]]);
            }
        );
    }
}