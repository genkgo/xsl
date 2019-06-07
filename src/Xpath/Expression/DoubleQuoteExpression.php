<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath\Expression;

use DOMNode;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\Xpath\ExpressionInterface;
use Genkgo\Xsl\Xpath\FunctionBuilder;
use Genkgo\Xsl\Xpath\Lexer;

final class DoubleQuoteExpression implements ExpressionInterface
{
    /**
     * @param Lexer $lexer
     * @return bool
     */
    public function supports(Lexer $lexer): bool
    {
        $expression = $lexer->current();
        if (\strlen($expression) <= 2) {
            return false;
        }

        return $expression[0] === "'" && $expression[-1] === "'" && \strpos(\substr($expression, 1, -1), "''") !== false;
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @param array $tokens
     * @return array
     */
    public function merge(Lexer $lexer, DOMNode $currentElement, array $tokens): array
    {
        $expression = $lexer->current();

        $callback = (new FunctionBuilder('php:function'))
            ->addArgument(PhpCallback::class . '::callStatic')
            ->addArgument(static::class)
            ->addArgument('singleQuote');

        return \array_merge(
            $tokens,
            [
                "concat('" . \str_replace("''", "', " . $callback->build() . ", '", \substr($expression, 1, -1)) . "')"
            ]
        );
    }

    public static function singleQuote(): string
    {
        return "'";
    }
}
