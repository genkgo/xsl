<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath\Expression;

use DOMNode;
use Genkgo\Xsl\Callback\ReturnXsSequenceFunction;
use Genkgo\Xsl\Xpath\ExpressionInterface;
use Genkgo\Xsl\Xpath\Lexer;
use Genkgo\Xsl\Xpath\MatchingIterator;
use Genkgo\Xsl\Xpath\SequenceConstructor;

final class SequenceExpression implements ExpressionInterface
{
    /**
     * @var ReturnXsSequenceFunction
     */
    private $sequenceConstructor;
    
    public function __construct()
    {
        $this->sequenceConstructor = new ReturnXsSequenceFunction(new SequenceConstructor());
    }

    /**
     * @param Lexer $lexer
     * @return bool
     */
    public function supports(Lexer $lexer): bool
    {
        if ($lexer->current() !== '(') {
            return false;
        }

        if ($lexer->key() > 0) {
            $whiteSpaceOrOpeningParenthesis = new MatchingIterator($lexer, '/\s|\(/', MatchingIterator::DIRECTION_DOWN);

            for ($i = 0, $j = 2; $i < $j; $i++) {
                if ($whiteSpaceOrOpeningParenthesis->valid() === false) {
                    return false;
                }

                if ($whiteSpaceOrOpeningParenthesis->key() < $lexer->key() - $i) {
                    return false;
                }

                $whiteSpaceOrOpeningParenthesis->next();
            }
        }

        $key = $lexer->key() + 1;
        $commaFound = false;

        while ($lexer->peek($key) !== '') {
            $nextToken = $lexer->peek($key);

            if ($nextToken === '(') {
                return false;
            }

            if ($nextToken === ',') {
                $commaFound = true;
            }

            if ($nextToken === ')') {
                return $commaFound;
            }

            $key++;
        }

        return false;
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @param array $tokens
     * @return array
     */
    public function merge(Lexer $lexer, DOMNode $currentElement, array $tokens): array
    {
        return \array_merge($tokens, $this->sequenceConstructor->serialize($lexer, $currentElement));
    }
}
