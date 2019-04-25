<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions;

use DOMElement;
use DOMNode;
use DOMXPath;
use Genkgo\Xsl\Callback\Arguments;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xpath\Lexer;
use Genkgo\Xsl\Xsl\ForEachGroup\Map as ForEachGroupMap;

final class GroupBy implements FunctionInterface
{
    /**
     * @var ForEachGroupMap
     */
    private $groups;

    /**
     * @param ForEachGroupMap $groups
     */
    public function __construct(ForEachGroupMap $groups)
    {
        $this->groups = $groups;
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array
     */
    public function serialize(Lexer $lexer, DOMNode $currentElement): array
    {
        return [$lexer->current()];
    }

    /**
     * @param Arguments $arguments
     * @param TransformationContext $context
     * @return true
     */
    public function call(Arguments $arguments, TransformationContext $context)
    {
        /** @var string $groupId */
        $groupId = $arguments->get(0);
        /** @var string $groupId */
        $iterationId = $arguments->get(1);
        /** @var DOMElement[] $elements */
        $elements = $arguments->get(2);
        /** @var string $elementId */
        $elementId = $arguments->get(3);
        /** @var string $groupBy */
        $groupBy = \base64_decode($arguments->get(4));
        /** @var string[] $namespaces */
        $namespaces = \json_decode(\base64_decode($arguments->get(5)) ?: '', true);

        $collection = $this->groups->get($groupId, $iterationId);

        foreach ($elements as $key => $element) {
            $xpath = new DOMXPath($element->ownerDocument);
            $xpath->registerNamespace('php', 'http://php.net/xpath');
            $xpath->registerPhpFunctions();

            $element->ownerDocument->documentElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:php', 'http://php.net/xpath');
            foreach ($namespaces as $prefix => $namespace) {
                $element->ownerDocument->documentElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:' . $prefix, $namespace);
                $xpath->registerNamespace($prefix, $namespace);
            }

            $expression = \str_replace(
                'position()',
                (string)($collection->countGroupItems() + 1),
                'string(' . $groupBy . ')'
            );

            $compiler = new Compiler($context->getFunctions());

            $groupingKey = $xpath->evaluate(
                $compiler->compile($expression, $element),
                $element
            );

            $collection->get($groupingKey)->addId($elementId);
        }

        return true;
    }
}
