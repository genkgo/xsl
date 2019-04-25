<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath\Expression;

use DOMDocument;
use DOMNode;
use Genkgo\Xsl\Callback\FunctionCollection;
use Genkgo\Xsl\Util\FetchNamespacesFromNode;
use Genkgo\Xsl\Xpath\ExpressionInterface;
use Genkgo\Xsl\Xpath\Lexer;

final class FunctionExpression implements ExpressionInterface
{
    /**
     * @var FunctionCollection
     */
    private $functions;

    /**
     * @param FunctionCollection $functions
     */
    public function __construct(FunctionCollection $functions)
    {
        $this->functions = $functions;
    }

    /**
     * @param Lexer $lexer
     * @return bool
     */
    public function supports(Lexer $lexer): bool
    {
        $nextToken = $lexer->peek($lexer->key() + 1);
        return $nextToken === '(';
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @param array $tokens
     * @return array
     */
    public function merge(Lexer $lexer, DOMNode $currentElement, array $tokens): array
    {
        return \array_merge($tokens, $this->createFunctionTokens($lexer, $currentElement));
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return string[]
     */
    private function createFunctionTokens(Lexer $lexer, DOMNode $currentElement)
    {
        if ($currentElement->ownerDocument instanceof DOMDocument === false) {
            throw new \UnexpectedValueException('Expecting currentElement attached to document');
        }

        $token = $lexer->current();
        $documentElement = $currentElement->ownerDocument->documentElement;
        $namespaces = FetchNamespacesFromNode::fetch($documentElement);
        $functionQname = $this->convertTokenToFunctionName($token, $namespaces);

        try {
            $dot = \strrpos($functionQname, ':');
            if ($dot !== false) {
                $namespace = \substr($functionQname, 0, $dot);
                $functionName = \substr($functionQname, $dot + 1);

                return $this->functions->get($namespace)->get($functionName)->serialize($lexer, $currentElement);
            } else {
                return $this->functions->get('')->get($functionQname)->serialize($lexer, $currentElement);
            }
        } catch (\InvalidArgumentException $e) {
            return [$token];
        }
    }

    /**
     * @param string $token
     * @param array $namespaces
     * @return string
     */
    private function convertTokenToFunctionName(string $token, array $namespaces): string
    {
        $functionName = \strpos($token, ':');

        if ($functionName !== false) {
            $prefix = \substr($token, 0, $functionName);
            if (isset($namespaces[$prefix])) {
                $token = $namespaces[$prefix] . ':' . \substr($token, $functionName + 1);
            }
        }

        return $token;
    }
}
