<?php
namespace Genkgo\Xsl\Xpath;

use DOMNode;

/**
 * Interface ExpressionInterface
 * @package Genkgo\Xsl\Xpath
 */
interface ExpressionInterface
{
    /**
     * @param Lexer $lexer
     * @return bool
     */
    public function supports(Lexer $lexer);

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @param array $tokens
     * @return array
     */
    public function merge(Lexer $lexer, DOMNode $currentElement, array $tokens);
}
