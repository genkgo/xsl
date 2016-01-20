<?php
namespace Genkgo\Xsl\Xpath\Expression;

use DOMNode;
use Genkgo\Xsl\Xpath\ExpressionInterface;
use Genkgo\Xsl\Xpath\ForLoopConstructor;
use Genkgo\Xsl\Xpath\Lexer;

final class ForLoopExpression implements ExpressionInterface
{
    /**
     * @var ForLoopConstructor
     */
    private $forLoopConstructor;

    /**
     *
     */
    public function __construct()
    {
        $this->forLoopConstructor = new ForLoopConstructor();
    }

    /**
     * @param Lexer $lexer
     * @return bool
     */
    public function supports(Lexer $lexer)
    {
        return ($lexer->current() === 'to' && preg_match('/\s/', $lexer->peek($lexer->key() - 1)) === 1);
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @param array $tokens
     * @return array
     */
    public function merge(Lexer $lexer, DOMNode $currentElement, array $tokens)
    {
        array_pop($tokens);
        array_pop($tokens);
        return array_merge($tokens, $this->forLoopConstructor->replace($lexer, $currentElement));
    }
}
