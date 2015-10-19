<?php
namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\Xpath\Lexer;

/**
 * Class SequenceFunction
 * @package Genkgo\Xsl\Callback
 */
class ReturnXsScalarFunction implements FunctionInterface
{
    /**
     * @var FunctionInterface
     */
    private $parentFunction;
    /**
     * @var bool
     */
    private $type = false;

    /**
     * @param FunctionInterface $parentFunction
     * @param $type
     */
    public function __construct(FunctionInterface $parentFunction, $type)
    {
        $this->parentFunction = $parentFunction;
        $this->type = $type;
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
                    if ($this->type !== null) {
                        $lexer->insert(['/', 'xs:' . $this->type], $currentKey + 2);
                        break;
                    }

                    $lexer->insert(['/', 'xs:*'], $currentKey + 2);
                    break;
                }
            }

            $currentKey++;
        }

        return $resultTokens;
    }
}
