<?php
declare(strict_types=1);

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
    
    public function __construct()
    {
        $this->forLoopConstructor = new ForLoopConstructor();
    }

    /**
     * @param Lexer $lexer
     * @return bool
     */
    public function supports(Lexer $lexer): bool
    {
        return ($lexer->current() === 'to' && \preg_match('/\s/', $lexer->peek($lexer->key() - 1)) === 1);
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @param array $tokens
     * @return array
     */
    public function merge(Lexer $lexer, DOMNode $currentElement, array $tokens): array
    {
        \array_splice(
            $tokens,
            $this->findStartExpression($tokens),
            0,
            $this->forLoopConstructor->serialize($lexer, $currentElement)
        );
        $tokens[] = ',';
        return $tokens;
    }

    /**
     * @param array $tokens
     * @return int
     */
    private function findStartExpression(array $tokens)
    {
        $breakTokens = [
            '[' => true,
            '=' => true,
        ];

        $level = 0;
        $position = \count($tokens);
        do {
            $token = $tokens[$position - 1];
            if ($level === 0 && isset($breakTokens[$token])) {
                return $position;
            }

            if ($token === ')') {
                $level++;
            }

            if ($token === '(') {
                $level--;
            }

            $tokens[] = $token;
            $position--;
        } while ($level >= 0 && isset($tokens[$position - 1]));

        return $position;
    }
}
