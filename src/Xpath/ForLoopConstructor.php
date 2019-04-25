<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath;

use DOMNode;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\Schema\XsSequence;
use Genkgo\Xsl\TransformationContext;

final class ForLoopConstructor implements FunctionInterface
{
    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array
     */
    public function serialize(Lexer $lexer, DOMNode $currentElement): array
    {
        $prepend = [];
        $prepend[] = 'php:function';
        $prepend[] = '(';
        $prepend[] = '\'';
        $prepend[] = PhpCallback::class.'::callStatic';
        $prepend[] = '\'';
        $prepend[] = ',';
        $prepend[] = '\'';
        $prepend[] = static::class;
        $prepend[] = '\'';
        $prepend[] = ',';
        $prepend[] = '\'';
        $prepend[] = 'newRange';
        $prepend[] = '\'';
        $prepend[] = ',';

        $append = [];
        $append[] = ')';
        $append[] = '/';
        $append[] = 'xs:sequence';
        $append[] = '/';
        $append[] = '*';

        $lexer->insert($append, $this->seekPositionUp($lexer, $lexer->key()));
        return $prepend;
    }

    /**
     * @param Lexer $lexer
     * @param int $position
     * @return int
     */
    private function seekPositionUp(Lexer $lexer, int $position): int
    {
        $level = 0;
        $position = $position + 2;
        do {
            $token = $lexer->peek($position);
            if ($token === ']') {
                break;
            }

            if ($token === '(') {
                $level++;
            }

            if ($token === ')') {
                $level--;
            }

            $position++;
        } while ($level >= 0 && $lexer->peek($position) !== '');

        return $position;
    }

    /**
     * @param array $arguments
     * @param TransformationContext $context
     * @return mixed
     */
    public function call(array $arguments, TransformationContext $context)
    {
        throw new \BadMethodCallException();
    }

    /**
     * @param int|float $first
     * @param int|float $last
     * @return XsSequence
     */
    public static function newRange($first, $last): XsSequence
    {
        if (isset($first[0]->textContent)) {
            $first = \trim($first[0]->textContent);
        }

        if (isset($last[0]->textContent)) {
            $last = \trim($last[0]->textContent);
        }

        return XsSequence::fromArray(\range($first, $last));
    }
}
