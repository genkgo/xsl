<?php
namespace Genkgo\Xsl;

use Genkgo\Xsl\Xpath\Lexer;

/**
 * Class ObjectFunction
 * @package Genkgo\Xsl
 */
class ObjectFunction extends AbstractFunction implements FunctionInterface
{
    /**
     * @param Lexer $lexer
     * @return array
     */
    public function replace(Lexer $lexer)
    {
        $resultTokens = [];
        $resultTokens[] = 'php:function';
        $resultTokens[] = '(';
        $resultTokens[] = '\'';
        $resultTokens[] = $this->class.'::'.$this->name;
        $resultTokens[] = '\'';

        $lexer->next();

        if ($lexer->peek($lexer->key() + 1) !== ')') {
            $resultTokens[] = ',';
        }

        return $resultTokens;
    }
}