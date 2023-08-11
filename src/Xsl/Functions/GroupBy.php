<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions;

use DOMElement;
use DOMXPath;
use Genkgo\Xsl\Callback\Arguments;
use Genkgo\Xsl\Schema\XsSequence;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xpath\Lexer;

final class GroupBy
{
    public static function distinct(Arguments $arguments, TransformationContext $context): XsSequence
    {
        $list = $arguments->get(0);
        /** @var string $groupBy */
        $groupBy = \base64_decode($arguments->get(1));

        /** @var string[] $namespaces */
        $namespaces = \json_decode(\base64_decode($arguments->get(2)) ?: '', true);

        /** @var DOMElement $current */
        $current = $arguments->get(3)[0];

        $compiler = new Compiler($context->getFunctions());
        $xpathExpression = $compiler->compileTokens(Lexer::tokenize('string(' . $groupBy . ')'), $current, $namespaces);

        $values = [];
        foreach ($list as $key => $element) {
            $xpath = new DOMXPath($element->ownerDocument);
            $xpath->registerNamespace('php', 'http://php.net/xpath');
            $xpath->registerPhpFunctions();

            foreach ($namespaces as $prefix => $namespace) {
                $xpath->registerNamespace($prefix, $namespace);
            }

            $groupingKey = $xpath->evaluate(\str_replace('position()', (string)($key + 1), $xpathExpression), $element);

            $values[] = $groupingKey;
        }

        return XsSequence::fromArray(\array_unique($values));
    }
}
