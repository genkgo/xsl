<?php
namespace Genkgo\Xsl\Xpath\Expression;

use DOMNode;
use Genkgo\Xsl\Callback\ReturnXsSequenceFunction;
use Genkgo\Xsl\Xpath\ExpressionInterface;
use Genkgo\Xsl\Xpath\Lexer;
use Genkgo\Xsl\Xpath\SequenceConstructor;

final class SequenceExpression implements ExpressionInterface {

    /**
     * @var ReturnXsSequenceFunction
     */
    private $sequenceConstructor;

    /**
     *
     */
    public function __construct () {
        $this->sequenceConstructor = new ReturnXsSequenceFunction(new SequenceConstructor());
    }

    /**
     * @param Lexer $lexer
     * @return bool
     */
    public function supports(Lexer $lexer)
    {
        if ($lexer->current() !== '(') {
            return false;
        }

        if ($lexer->key() === 0) {
            return true;
        } else {
            $prevToken = $lexer->peek($lexer->key() - 1);
            return $prevToken === '(' || preg_match('/\s/', $prevToken) === 1;
        }
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @param array $tokens
     * @return array
     */
    public function merge(Lexer $lexer, DOMNode $currentElement, array $tokens)
    {
        return array_merge($tokens, $this->sequenceConstructor->replace($lexer, $currentElement));
    }
}