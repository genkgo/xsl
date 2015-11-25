<?php
namespace Genkgo\Xsl\Xpath;

use DOMNode;
use Genkgo\Xsl\Callback\ReplaceFunctionInterface;
use Genkgo\Xsl\Callback\ReturnXsSequenceFunction;
use Genkgo\Xsl\Util\FetchNamespacesFromNode;
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
     * @var SequenceConstructor
     */
    private $sequenceConstructor;

    /**
     * @param FunctionMap $functions
     */
    public function __construct(FunctionMap $functions)
    {
        $this->functions = $functions;
        $this->sequenceConstructor = new ReturnXsSequenceFunction(new SequenceConstructor());
    }

    /**
     * @param $xpathExpression
     * @param DOMNode $currentElement
     * @return string
     */
    public function compile($xpathExpression, DOMNode $currentElement)
    {
        $resultTokens = [];
        $lexer = Lexer::tokenize($xpathExpression);
        foreach ($lexer as $token) {
            $nextToken = $lexer->peek($lexer->key() + 1);
            if ($nextToken === '(') {
                $resultTokens = array_merge($resultTokens, $this->createFunctionTokens($lexer, $token, $currentElement));
                continue;
            }

            if ($this->isSequence($lexer)) {
                $resultTokens = array_merge($resultTokens, $this->createSequenceTokens($lexer, $currentElement));
                continue;
            }

            $resultTokens[] = $token;
        }

        return trim(implode('', $resultTokens));
    }

    /**
     * @param Lexer $lexer
     * @param $token
     * @param $currentElement
     * @return string[]
     */
    private function createFunctionTokens (Lexer $lexer, $token, $currentElement) {
        $namespaces = FetchNamespacesFromNode::fetch($currentElement);
        $functionName = $this->convertTokenToFunctionName($token, $namespaces);

        if ($this->functions->has($functionName)) {
            $function = $this->functions->get($functionName);
            if ($function instanceof ReplaceFunctionInterface) {
                return $function->replace($lexer, $currentElement);
            }
        }

        return [$token];
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

    /**
     * @param Lexer $lexer
     * @return bool
     */
    private function isSequence (Lexer $lexer) {
        if ($lexer->current() !== '(') {
            return false;
        }

        if ($lexer->key() === 0) {
            return true;
        } else {
//            $prevToken = $lexer->peek($lexer->key() - 1);
//            return preg_match('/[\i-[:]][\c-[:]]*/', $prevToken) === 0;
            return false;
        }
    }

    /**
     * @param Lexer $lexer
     * @param $currentElement
     * @return string[]
     */
    private function createSequenceTokens (Lexer $lexer, $currentElement) {
        return $this->sequenceConstructor->replace($lexer, $currentElement);
    }
}
