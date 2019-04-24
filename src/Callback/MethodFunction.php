<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use DOMNode;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xpath\Lexer;

final class MethodFunction implements FunctionInterface, MethodCallInterface, ReplaceFunctionInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var MethodCallInterface
     */
    private $caller;

    /**
     * @param string $name
     * @param MethodCallInterface $caller
     */
    public function __construct(string $name, MethodCallInterface $caller)
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
     * @param array $arguments
     * @param TransformationContext $context
     * @return mixed
     */
    public function call(array $arguments, TransformationContext $context)
    {
        return $this->caller->call($arguments, $context);
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
}
