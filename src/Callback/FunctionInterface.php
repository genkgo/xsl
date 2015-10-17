<?php
namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\DocumentContext;
use Genkgo\Xsl\Xpath\Lexer;

/**
 * Interface FunctionInterface
 * @package Genkgo\Xsl\Callback
 */
interface FunctionInterface
{
    /**
     * @return string
     */
    public function getXpathMethod();

    /**
     * @param Lexer $lexer
     * @param DocumentContext $context
     * @return array|\string[]
     */
    public function replace(Lexer $lexer, DocumentContext $context);
}
