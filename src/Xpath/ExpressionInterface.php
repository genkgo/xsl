<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath;

use DOMNode;

interface ExpressionInterface
{
    /**
     * @param Lexer $lexer
     * @return bool
     */
    public function supports(Lexer $lexer): bool;

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @param array $tokens
     * @return array
     */
    public function merge(Lexer $lexer, DOMNode $currentElement, array $tokens): array;
}
