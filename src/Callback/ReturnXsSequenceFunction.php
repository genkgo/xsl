<?php
namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\Xpath\Lexer;

/**
 * Class ReturnXsSequenceFunction
 * @package Genkgo\Xsl\Callback
 */
class ReturnXsSequenceFunction implements ReplaceFunctionInterface
{
    /**
     * @var FunctionInterface
     */
    private $parentFunction;

    /**
     * @param ReplaceFunctionInterface $parentFunction
     */
    public function __construct(ReplaceFunctionInterface $parentFunction)
    {
        $this->parentFunction = $parentFunction;
    }

    /**
     * @param Lexer $lexer
     * @return array
     */
    public function replace(Lexer $lexer)
    {
        $resultTokens = $this->parentFunction->replace($lexer);

        $currentKey = $lexer->key();
        $level = 1;

        while (true) {
            $item = $lexer->peek($currentKey + 1);

            if ($item === null) {
                break;
            }

            if ($item === '(') {
                $level++;
            }

            if ($item === ')') {
                $level--;

                if ($level === 0) {
                    $lexer->insert(['/', 'xs:sequence', '/', '*'], $currentKey + 2);
                    break;
                }
            }

            $currentKey++;
        }

        return $resultTokens;
    }
}
