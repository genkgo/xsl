<?php
namespace Genkgo\Xsl\Callback;

use Closure;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xpath\Lexer;

/**
 * Interface ClosureFunction
 * @package Genkgo\Xsl\Callback
 */
final class ClosureFunction implements FunctionInterface, ReplaceFunctionInterface, MethodCallInterface
{

    private $name;
    private $replacer;
    private $callback;

    public function __construct ($name, ReplaceFunctionInterface $replacer, Closure $callback) {
        $this->name = $name;
        $this->replacer = $replacer;
        $this->callback = $callback;
    }


    /**
     * @param FunctionMap $functionMap
     * @return void
     */
    public function register (FunctionMap $functionMap) {
        $functionMap->set($this->name, $this);
    }

    /**
     * @param Lexer $lexer
     * @return array|string[]
     */
    public function replace(Lexer $lexer)
    {
        return $this->replacer->replace($lexer);
    }

    /**
     * @param $arguments
     * @param TransformationContext $context
     * @return mixed
     */
    public function call($arguments, TransformationContext $context)
    {
        $callback = $this->callback;
        return $callback($arguments, $context);
    }
}
