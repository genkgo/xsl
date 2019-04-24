<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use DOMNode;
use Genkgo\Xsl\Xpath\Lexer;

final class ReturnXsScalarFunction implements ReplaceFunctionInterface
{
    /**
     * @var ReplaceFunctionInterface
     */
    private $parentFunction;

    /**
     * @var string
     */
    private $type;

    /**
     * @param ReplaceFunctionInterface $parentFunction
     * @param string $type
     */
    public function __construct(ReplaceFunctionInterface $parentFunction, string $type)
    {
        $this->parentFunction = $parentFunction;
        $this->type = ($type === '' ? '*' : $type);
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array
     */
    public function replace(Lexer $lexer, DOMNode $currentElement)
    {
        $resultTokens = $this->parentFunction->replace($lexer, $currentElement);

        $currentKey = $lexer->key();
        $level = 1;

        while (true) {
            $item = $lexer->peek($currentKey + 1);

            if ($item === '') {
                break;
            }

            if ($item === '(') {
                $level++;
            }

            if ($item === ')') {
                $level--;

                if ($level === 0) {
                    $lexer->insert(['/', 'xs:' . $this->type], $currentKey + 2);
                    break;
                }
            }

            $currentKey++;
        }

        return $resultTokens;
    }
}
