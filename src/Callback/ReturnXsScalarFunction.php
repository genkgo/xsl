<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use DOMNode;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Xpath\Lexer;

final class ReturnXsScalarFunction implements FunctionInterface
{
    /**
     * @var FunctionInterface
     */
    private $parentFunction;

    /**
     * @var string
     */
    private $type;

    /**
     * @param FunctionInterface $parentFunction
     * @param string $type
     */
    public function __construct(FunctionInterface $parentFunction, string $type)
    {
        $this->parentFunction = $parentFunction;
        $this->type = ($type === '' ? '*' : $type);
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array
     */
    public function serialize(Lexer $lexer, DOMNode $currentElement): array
    {
        $resultTokens = $this->parentFunction->serialize($lexer, $currentElement);

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

    /**
     * @param Arguments $arguments
     * @param TransformationContext $context
     * @return mixed
     */
    public function call(Arguments $arguments, TransformationContext $context)
    {
        return $this->parentFunction->call($arguments, $context);
    }
}
