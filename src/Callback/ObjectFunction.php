<?php
namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\DocumentContext;
use Genkgo\Xsl\Xpath\Lexer;

/**
 * Class ObjectFunction
 * @package Genkgo\Xsl\Callback
 */
class ObjectFunction extends AbstractFunction implements FunctionInterface
{
    /**
     * @param Lexer $lexer
     * @param DocumentContext $context
     * @return array
     */
    public function replace(Lexer $lexer, DocumentContext $context)
    {
        $resultTokens = [];
        $resultTokens[] = 'php:function';
        $resultTokens[] = '(';
        $resultTokens[] = '\'';
        $resultTokens[] = PhpCallback::class.'::call';
        $resultTokens[] = '\'';
        $resultTokens[] = ',';
        $resultTokens[] = '\'';
        $resultTokens[] = $this->class;
        $resultTokens[] = '\'';
        $resultTokens[] = ',';
        $resultTokens[] = '\'';
        $resultTokens[] = $this->name;
        $resultTokens[] = '\'';

        $lexer->next();

        if ($lexer->peek($lexer->key() + 1) !== ')') {
            $resultTokens[] = ',';
        }

        return $resultTokens;
    }
}
