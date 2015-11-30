<?php
namespace Genkgo\Xsl\Xpath;

use DOMNode;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xpath\Expression\ForLoopExpression;
use Genkgo\Xsl\Xpath\Expression\FunctionExpression;
use Genkgo\Xsl\Xpath\Expression\SequenceExpression;

/**
 * Class Compiler
 * @package Genkgo\Xsl\Xpath
 */
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
     * @param $xpathExpression
     * @param DOMNode $currentElement
     * @return string
     */
    public function compile($xpathExpression, DOMNode $currentElement)
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

        return trim(implode('', $resultTokens));
    }

}
