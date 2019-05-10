<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath;

use DOMNode;
use Genkgo\Xsl\Callback\Arguments;
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
     * @param Arguments $arguments
     * @param TransformationContext $context
     * @return mixed
     */
    public function call(Arguments $arguments, TransformationContext $context)
    {
        throw new \BadMethodCallException();
    }

    /**
     * @param Arguments $arguments
     * @return XsSequence
     */
    public static function newRange(Arguments $arguments): XsSequence
    {
        $first = $arguments->castFromSchemaType(0);
        $last = $arguments->castFromSchemaType(1);

        return XsSequence::fromArray(\range($first, $last));
    }
}
