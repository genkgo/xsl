<?php
namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\Xpath\Lexer;

/**
 * Interface FunctionInterface
 * @package Genkgo\Xsl\Callback
 */
interface FunctionInterface
{
    /**
     * @param Lexer $lexer
     * @return array|\string[]
     */
    public function replace(Lexer $lexer);
}
