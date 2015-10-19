<?php
namespace Genkgo\Xsl\Xsl\Functions;

use DOMElement;
use DOMXPath;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\Callback\InvokableInterface;
use Genkgo\Xsl\Schema\XsSequence;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Util\FetchNamespacesFromDocument;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xpath\Lexer;

class GroupBy implements FunctionInterface, InvokableInterface {

    private $compiler;

    public function __construct (Compiler $compiler) {
        $this->compiler = $compiler;
    }

    /**
     * @param Lexer $lexer
     * @return array|\string[]
     */
    public function replace(Lexer $lexer)
    {
        ;
    }

    /**
     * @param $arguments
     * @return XsSequence
     * @throws \Genkgo\Xsl\Schema\Exception\UnknownSequenceItemException
     */
    public function call($arguments)
    {
        /** @var TransformationContext $context */
        $context = $arguments[0];
        /** @var DOMElement[] $elements */
        $elements = $arguments[1];
        /** @var string $groupBy */
        $groupBy = $arguments[2];

        if (count($elements) === 0) {
            return new XsSequence();
        }

        $groupBy = base64_decode($groupBy);
        $groups = [];
        $currentGroup = [];

        $namespaces = FetchNamespacesFromDocument::fetch($elements[0]->ownerDocument);

        foreach ($elements as $key => $element) {
            $xpath = new DOMXPath($element->ownerDocument);
            $xpath->registerPhpFunctions($context->getPhpFunctions());

            $groupingKey = $xpath->evaluate(
                $this->compiler->compile('string(' . $groupBy . ')', $namespaces),
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
                $context->getFunctions()->get('current-grouping-key')->setForElement($item, $groupingKey);
                $context->getFunctions()->get('current-group')->setForElement($item, $groups[$groupingKey]);
            }
        );
    }
}