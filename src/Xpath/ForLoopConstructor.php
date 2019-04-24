<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath;

use DOMNode;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\Callback\ReplaceFunctionInterface;
use Genkgo\Xsl\Schema\XsSequence;

final class ForLoopConstructor implements ReplaceFunctionInterface
{
    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array|string[]
     */
    public function replace(Lexer $lexer, DOMNode $currentElement)
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
        $prepend[] = 'call';
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
     * @param mixed $first
     * @param mixed $last
     * @return XsSequence
     */
    public static function call($first, $last)
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
