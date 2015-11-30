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
     * @var ReturnXsSequenceFunction
     */
    private $sequenceConstructor;
    /**
     * @var ReturnXsSequenceFunction
     */
    private $forLoopConstructor;

    /**
     * @param FunctionMap $functions
     */
    public function __construct(FunctionMap $functions)
    {
        $this->functions = $functions;
        $this->sequenceConstructor = new ReturnXsSequenceFunction(new SequenceConstructor());
        $this->forLoopConstructor = new ForLoopConstructor();
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
                $resultTokens = array_merge($resultTokens, $this->createFunctionTokens($lexer, $currentElement));
                continue;
            }

            if ($this->isSequence($lexer)) {
                $resultTokens = array_merge($resultTokens, $this->createSequenceTokens($lexer, $currentElement));
                continue;
            }

            if ($this->isForLoop($lexer)) {
                array_pop($resultTokens);
                array_pop($resultTokens);
                $resultTokens = array_merge($resultTokens, $this->createForLoop($lexer, $currentElement));
                continue;
            }

            $resultTokens[] = $token;
        }

        return trim(implode('', $resultTokens));
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return string[]
     */
    private function createFunctionTokens (Lexer $lexer, DOMNode $currentElement) {
        $token = $lexer->current();
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
            $prevToken = $lexer->peek($lexer->key() - 1);
            return $prevToken === '(' || preg_match('/\s/', $prevToken) === 1;
        }
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return string[]
     */
    private function createSequenceTokens (Lexer $lexer, DOMNode $currentElement) {
        return $this->sequenceConstructor->replace($lexer, $currentElement);
    }

    private function isForLoop(Lexer $lexer)
    {
        return ($lexer->current() === 'to' && preg_match('/\s/', $lexer->peek($lexer->key() - 1)) === 1);
    }

    private function createForLoop(Lexer $lexer, DOMNode $currentElement)
    {
        return $this->forLoopConstructor->replace($lexer, $currentElement);
    }
}
