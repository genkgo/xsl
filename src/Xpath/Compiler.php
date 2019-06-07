<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath;

use DOMNode;
use Genkgo\Xsl\Callback\FunctionCollection;
use Genkgo\Xsl\Xpath\Expression\DoubleQuoteExpression;
use Genkgo\Xsl\Xpath\Expression\ForLoopExpression;
use Genkgo\Xsl\Xpath\Expression\FunctionExpression;
use Genkgo\Xsl\Xpath\Expression\SequenceExpression;

final class Compiler
{
    /**
     * @var ExpressionInterface[]
     */
    private $expressions = [];

    /**
     * @param FunctionCollection $functions
     */
    public function __construct(FunctionCollection $functions)
    {
        $this->expressions = [
            new FunctionExpression($functions),
            new SequenceExpression(),
            new ForLoopExpression(),
            new DoubleQuoteExpression(),
        ];
    }

    /**
     * @param string $xpathExpression
     * @param DOMNode $currentElement
     * @return string
     */
    public function compile(string $xpathExpression, DOMNode $currentElement): string
    {
        $resultTokens = [];
        $lexer = Lexer::tokenize($xpathExpression);
        foreach ($lexer as $token) {
            foreach ($this->expressions as $expression) {
                if ($expression->supports($lexer)) {
                    $resultTokens = $expression->merge($lexer, $currentElement, $resultTokens);
                    continue 2;
                }
            }
            $resultTokens[] = $token;
        }

        return \trim(\implode('', $resultTokens));
    }
}
