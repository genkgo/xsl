<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath;

use DOMNode;
use Genkgo\Xsl\Callback\Arguments;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\Schema\XsSequence;
use Genkgo\Xsl\TransformationContext;

final class SequenceConstructor implements FunctionInterface
{
    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array
     */
    public function serialize(Lexer $lexer, DOMNode $currentElement): array
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
        $resultTokens[] = 'newSequence';
        $resultTokens[] = '\'';

        if ($lexer->peek($lexer->key() + 1) !== ')') {
            $resultTokens[] = ',';
        }

        return $resultTokens;
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
     * @param TransformationContext $context
     * @return mixed
     */
    public static function newSequence(Arguments $arguments, TransformationContext $context)
    {
        return XsSequence::fromArray($arguments->unpack());
    }
}
