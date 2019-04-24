<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use DOMNode;
use Genkgo\Xsl\Xpath\Lexer;

/**
 * Interface ReplaceFunctionInterface
 */
interface ReplaceFunctionInterface
{
    /**
     * @param Lexer $lexer
     * @return string[]
     */
    public function replace(Lexer $lexer, DOMNode $currentElement);
}
