<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use Closure;
use DOMNode;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xpath\Lexer;

final class ClosureFunction implements FunctionInterface, ReplaceFunctionInterface, MethodCallInterface
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
    public function __construct($name, Closure $callback)
    {
        $this->name = $name;
        $this->callback = $callback;
    }

    /**
     * @param FunctionMap $functionMap
     * @return void
     */
    public function register(FunctionMap $functionMap)
    {
        $functionMap->set($this->name, $this);
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array|string[]
     */
    public function replace(Lexer $lexer, DOMNode $currentElement)
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
     * @param array $arguments
     * @param TransformationContext $context
     * @return mixed
     */
    public function call(array $arguments, TransformationContext $context)
    {
        $callback = $this->callback;
        return $callback($arguments, $context);
    }
}
