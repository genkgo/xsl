<?php
namespace Genkgo\Xsl\Xpath;

use DOMNode;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\Callback\ReplaceFunctionInterface;
use Genkgo\Xsl\Schema\XsSequence;

/**
 * Class Sequence
 * @package Genkgo\Xsl\Xpath
 */
final class ForLoopConstructor implements ReplaceFunctionInterface
{
    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array|\string[]
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
        $resultTokens[] = ',';
        $resultTokens[] = $lexer->peek($lexer->key() - 2);
        $resultTokens[] = ',';
        $resultTokens[] = $lexer->peek($lexer->key() + 2);
        $resultTokens[] = ')';
        $resultTokens[] = '/';
        $resultTokens[] = 'xs:sequence';
        $resultTokens[] = '/';
        $resultTokens[] = '*';

        $lexer->seek($lexer->key() + 2);

        return $resultTokens;
    }

    /**
     * @param $first
     * @param $last
     * @return mixed
     * @throws \Genkgo\Xsl\Schema\Exception\UnknownSequenceItemException
     */
    public static function call($first, $last)
    {
        return XsSequence::fromArray(range($first, $last));
    }
}
