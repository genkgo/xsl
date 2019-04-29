<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use DOMNode;
use Genkgo\Xsl\Xpath\Lexer;

abstract class AbstractFunction implements FunctionInterface
{
    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array|string[]
     */
    public function serialize(Lexer $lexer, DOMNode $currentElement): array
    {
        $resultTokens = [
            'php:function',
            '(',
            '\'',
            PhpCallback::class.'::call',
            '\'',
            ',',
            '\'',
            $this->getName(),
            '\''
        ];

        $lexer->next();

        if ($lexer->peek($lexer->key() + 1) !== ')') {
            $resultTokens[] = ',';
        }

        return $resultTokens;
    }

    /**
     * @return string
     */
    abstract protected function getName(): string;
}
