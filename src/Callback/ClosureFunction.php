<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use Closure;
use DOMNode;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Xpath\Lexer;

final class ClosureFunction implements FunctionInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Closure
     */
    private $callback;

    /**
     * @param string $name
     * @param Closure $callback
     */
    public function __construct(string $name, Closure $callback)
    {
        $this->name = $name;
        $this->callback = $callback;
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array|string[]
     */
    public function serialize(Lexer $lexer, DOMNode $currentElement): array
    {
        $resultTokens = [];
        $resultTokens[] = 'php:function';
        $resultTokens[] = '(';
        $resultTokens[] = '\'';
        $resultTokens[] = PhpCallback::class.'::call';
        $resultTokens[] = '\'';
        $resultTokens[] = ',';
        $resultTokens[] = '\'';
        $resultTokens[] = $this->name;
        $resultTokens[] = '\'';

        $lexer->next();

        if ($lexer->peek($lexer->key() + 1) !== ')') {
            $resultTokens[] = ',';
        }

        return $resultTokens;
    }

    /**
     * @param Arguments $arguments
     * @param TransformationContext $context
     * @return mixed
     */
    public function call(Arguments $arguments, TransformationContext $context)
    {
        return \call_user_func($this->callback, $arguments, $context);
    }
}
