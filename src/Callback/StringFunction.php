<?php
namespace Genkgo\Xsl\Callback;

use DOMNode;
use Genkgo\Xsl\Xpath\Lexer;

/**
 * Class StringFunction
 * @package Genkgo\Xsl\Callback
 */
final class StringFunction implements ReplaceFunctionInterface
{
    /**
     * @var bool
     */
    private $camelize = false;
    /**
     * @var array
     */
    private $callback;

    /**
     * @param array $callback
     * @param bool $camelize
     */
    public function __construct(array $callback, $camelize = false)
    {
        $this->callback = $callback;
        $this->camelize = $camelize;
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array|\string[]
     */
    public function replace(Lexer $lexer, DOMNode $currentElement)
    {
        if ($this->camelize === true) {
            $methodName = $this->convertToCamel($this->callback[1]);
        } else {
            $methodName = $this->callback[1];
        }

        $resultTokens = [];
        $resultTokens[] = 'php:functionString';
        $resultTokens[] = '(';
        $resultTokens[] = '\'';
        $resultTokens[] = PhpCallback::class.'::callStatic';
        $resultTokens[] = '\'';
        $resultTokens[] = ',';
        $resultTokens[] = '\'';
        $resultTokens[] = $this->callback[0];
        $resultTokens[] = '\'';
        $resultTokens[] = ',';
        $resultTokens[] = '\'';
        $resultTokens[] = $methodName;
        $resultTokens[] = '\'';

        $lexer->next();

        if ($lexer->peek($lexer->key() + 1) !== ')') {
            $resultTokens[] = ',';
        }

        return $resultTokens;
    }

    /**
     * @param string $methodName
     * @return string
     */
    private function convertToCamel($methodName)
    {
        $methodName = ucwords(str_replace(['-', '_'], ' ', $methodName));
        $methodName = lcfirst(str_replace(' ', '', $methodName));

        return $methodName;
    }
}
