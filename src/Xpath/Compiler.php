<?php
namespace Genkgo\Xsl\Xpath;

use Genkgo\Xsl\Context;
use Genkgo\Xsl\FunctionInterface;

/**
 * Class Compiler
 * @package Genkgo\Xsl\Xpath
 */
final class Compiler
{
    /**
     * @var FunctionInterface[]
     */
    private $functions = [];

    /**
     *
     */
    public function __construct()
    {
        $this->addFunctions(Functions::supportedFunctions());
    }

    /**
     * @param FunctionInterface[] $functions
     * @return $this
     */
    public function addFunctions($functions)
    {
        foreach ($functions as $function) {
            $this->functions[$function->getXpathMethod()] = $function;
        }
        return $this;
    }

    /**
     * @param FunctionInterface[] $functions
     * @param $namespace
     * @return $this
     */
    public function addNsFunctions(array $functions, $namespace)
    {
        foreach ($functions as $function) {
            $this->functions[$namespace . ':' . $function->getXpathMethod()] = $function;
        }
        return $this;
    }

    /**
     * @param $xpathExpression
     * @param Context $context
     * @return string
     */
    public function compile($xpathExpression, Context $context)
    {
        $resultTokens = [];
        $lexer = Lexer::tokenize($xpathExpression);
        foreach ($lexer as $token) {
            $functionName = $this->convertTokenToFunctionName($token, $context);

            if (isset($this->functions[$functionName])) {
                $function = $this->functions[$functionName];
                $resultTokens = array_merge($resultTokens, $function->replace($lexer));
            } else {
                $resultTokens[] = $token;
            }
        }

        return implode('', $resultTokens);
    }

    /**
     * @param $token
     * @param Context $context
     * @return string
     */
    private function convertTokenToFunctionName($token, Context $context)
    {
        $functionName = strpos($token, ':');

        if ($functionName !== false) {
            $namespace = $context->getNamespace(substr($token, 0, $functionName));
            if ($namespace !== null) {
                $token = $namespace . ':' . substr($token, $functionName + 1);
            }
        }

        return $token;
    }
}
