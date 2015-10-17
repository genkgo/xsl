<?php
namespace Genkgo\Xsl\Xpath;

use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\DocumentContext;
use Genkgo\Xsl\Exception\UnknownNamespaceException;

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
     * @param DocumentContext $context
     * @return string
     */
    public function compile($xpathExpression, DocumentContext $context)
    {
        $resultTokens = [];
        $lexer = Lexer::tokenize($xpathExpression);
        foreach ($lexer as $token) {
            $nextToken = $lexer->peek($lexer->key() + 1);
            if ($nextToken === '(') {
                $functionName = $this->convertTokenToFunctionName($token, $context);

                if (isset($this->functions[$functionName])) {
                    $function = $this->functions[$functionName];
                    $resultTokens = array_merge($resultTokens, $function->replace($lexer, $context));
                    continue;
                }
            }

            $resultTokens[] = $token;
        }

        return implode('', $resultTokens);
    }

    /**
     * @param $token
     * @param DocumentContext $context
     * @return string
     */
    private function convertTokenToFunctionName($token, DocumentContext $context)
    {
        $functionName = strpos($token, ':');

        if ($functionName !== false) {
            try {
                $namespace = $context->getNamespace(substr($token, 0, $functionName));
                $token = $namespace . ':' . substr($token, $functionName + 1);
            } catch (UnknownNamespaceException $e) {
            }
        }

        return $token;
    }
}
