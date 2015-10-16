<?php
namespace Genkgo\Xsl;

use Genkgo\Xsl\Xpath\Lexer;

/**
 * Interface FunctionInterface
 * @package Genkgo\Xsl
 */
interface FunctionInterface
{
    /**
     * @return string
     */
    public function getXpathMethod();

    /**
     * @param Lexer $lexer
     * @return array|string[]
     */
    public function replace(Lexer $lexer);
}
