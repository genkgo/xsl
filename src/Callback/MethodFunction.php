<?php
namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xpath\Lexer;

/**
 * Interface FunctionCall
 * @package Genkgo\Xsl\Callback
 */
final class MethodFunction implements FunctionInterface, ReplaceFunctionInterface, MethodCallInterface
{

    private $name;
    private $replacer;
    private $caller;

    public function __construct ($name, ReplaceFunctionInterface $replacer, MethodCallInterface $caller) {
        $this->name = $name;
        $this->replacer = $replacer;
        $this->caller = $caller;
    }


    /**
     * @param FunctionMap $functionMap
     * @return void
     */
    public function register (FunctionMap $functionMap) {
        $functionMap->set($this->name, $this);
    }

    /**
     * @param $arguments
     * @param TransformationContext $context
     * @return mixed
     */
    public function call($arguments, TransformationContext $context)
    {
        return $this->caller->call($arguments, $context);
    }

    /**
     * @param Lexer $lexer
     * @return array|string[]
     */
    public function replace(Lexer $lexer)
    {
        return $this->replacer->replace($lexer);
    }
}
