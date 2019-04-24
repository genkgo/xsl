<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath;

use DOMNode;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\Callback\ReplaceFunctionInterface;
use Genkgo\Xsl\Schema\XsSequence;

final class SequenceConstructor implements ReplaceFunctionInterface
{
    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array|string[]
     */
    public function replace(Lexer $lexer, DOMNode $currentElement)
    {
        $resultTokens = [];
        $resultTokens[] = 'php:function';
        $resultTokens[] = '(';
        $resultTokens[] = '\'';
        $resultTokens[] = PhpCallback::class.'::callStatic';
        $resultTokens[] = '\'';
        $resultTokens[] = ',';
        $resultTokens[] = '\'';
        $resultTokens[] = static::class;
        $resultTokens[] = '\'';
        $resultTokens[] = ',';
        $resultTokens[] = '\'';
        $resultTokens[] = 'call';
        $resultTokens[] = '\'';

        if ($lexer->peek($lexer->key() + 1) !== ')') {
            $resultTokens[] = ',';
        }

        return $resultTokens;
    }

    /**
     * @param mixed ...$arguments
     * @return XsSequence
     */
    public static function call(...$arguments)
    {
        return XsSequence::fromArray($arguments);
    }
}
