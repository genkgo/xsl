<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use DOMNode;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Xpath\Lexer;

interface FunctionInterface
{
    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array
     */
    public function serialize(Lexer $lexer, DOMNode $currentElement): array;

    /**
     * @param Arguments $arguments
     * @param TransformationContext $context
     * @return mixed
     */
    public function call(Arguments $arguments, TransformationContext $context);
}
