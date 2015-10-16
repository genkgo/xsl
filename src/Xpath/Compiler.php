<?php
namespace Genkgo\Xsl\Xpath;

use Genkgo\Xsl\Context;
use Genkgo\Xsl\FunctionInterface;

/**
 * Class Compiler
 * @package Genkgo\Xsl\Xpath
 */
final class Compiler {

    /**
     * @var FunctionInterface[]
     */
    private $functions = [];

    public function __construct () {
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
    public function compile ($xpathExpression, Context $context) {
        $resultTokens = [];
        $lexer = Lexer::tokenize($xpathExpression);
        foreach ($lexer as $token) {
            $namespacedMethod = strpos($token, ':');
            if ($namespacedMethod !== false) {
                $namespace = $context->getNamespace(substr($token, 0, $namespacedMethod));
                if ($namespace !== null) {
                    $token = $namespace . ':' . substr($token, $namespacedMethod + 1);
                }
            }
            if (isset($this->functions[$token])) {
                $function = $this->functions[$token];
                $resultTokens = array_merge($resultTokens, $function->replace($lexer));
            } else {
                $resultTokens[] = $token;
            }
        }

        return implode('', $resultTokens);
    }
}