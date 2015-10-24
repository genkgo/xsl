<?php
namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xpath\Lexer;

/**
 * Interface MethodFunction
 * @package Genkgo\Xsl\Callback
 */
final class MethodFunction implements FunctionInterface, MethodCallInterface, ReplaceFunctionInterface
{
    private $name;
    private $caller;

    public function __construct($name, MethodCallInterface $caller)
    {
        $this->name = $name;
        $this->caller = $caller;
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
}
