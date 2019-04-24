<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath;

use DOMNode;
use Genkgo\Xsl\Util\FunctionMap;
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
     * @param FunctionMap $functions
     */
    public function __construct(FunctionMap $functions)
    {
        $this->expressions = [
            new FunctionExpression($functions),
            new SequenceExpression(),
            new ForLoopExpression()
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
