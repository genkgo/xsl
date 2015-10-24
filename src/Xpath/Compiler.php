<?php
namespace Genkgo\Xsl\Xpath;

use Genkgo\Xsl\Util\FunctionMap;

/**
 * Class Compiler
 * @package Genkgo\Xsl\Xpath
 */
final class Compiler
{
    /**
     * @var FunctionMap
     */
    private $functions;

    /**
     * @param FunctionMap $functions
     */
    public function __construct(FunctionMap $functions)
    {
        $this->functions = $functions;
    }

    /**
     * @param $xpathExpression
     * @param $namespaces
     * @return string
     */
    public function compile($xpathExpression, $namespaces)
    {
        $resultTokens = [];
        $lexer = Lexer::tokenize($xpathExpression);
        foreach ($lexer as $token) {
            $nextToken = $lexer->peek($lexer->key() + 1);
            if ($nextToken === '(') {
                $functionName = $this->convertTokenToFunctionName($token, $namespaces);

                if ($this->functions->has($functionName)) {
                    $function = $this->functions->get($functionName);
                    $resultTokens = array_merge($resultTokens, $function->replace($lexer));
                    continue;
                }
            }

            $resultTokens[] = $token;
        }

        return trim(implode('', $resultTokens));
    }

    /**
     * @param $token
     * @param array $namespaces
     * @return string
     */
    private function convertTokenToFunctionName($token, array $namespaces)
    {
        $functionName = strpos($token, ':');

        if ($functionName !== false) {
            $prefix = substr($token, 0, $functionName);
            if (isset($namespaces[$prefix])) {
                $token = $namespaces[$prefix] . ':' . substr($token, $functionName + 1);
            }
        }

        return $token;
    }
}
