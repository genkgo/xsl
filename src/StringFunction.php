<?php
namespace Genkgo\Xsl;

use Genkgo\Xsl\Xpath\Lexer;

/**
 * Class StringFunction
 * @package Genkgo\Xsl
 */
class StringFunction extends AbstractFunction implements FunctionInterface
{
    /**
     * @param Lexer $lexer
     * @return array
     */
    public function replace(Lexer $lexer)
    {
        $resultTokens = [];
        $resultTokens[] = 'php:functionString';
        $resultTokens[] = '(';
        $resultTokens[] = '\'';
        $resultTokens[] = $this->class.'::' . $this->name;
        $resultTokens[] = '\'';

        $lexer->next();
        if ($lexer->peek($lexer->key() + 1) !== ')') {
            $resultTokens[] = ',';
        }

        return $resultTokens;
    }
}
