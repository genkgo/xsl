<?php
namespace Genkgo\Xsl\Xpath;

use Genkgo\Xsl\FunctionInterface;
use Genkgo\Xsl\StringFunction;

/**
 * Class Compiler
 * @package Genkgo\Xsl\Xpath
 */
final class Compiler {

    /**
     * @var FunctionInterface[]
     */
    private $functions = [];

    /**
     *
     */
    public function __construct () {
        $this
            ->addFunction(new StringFunction('abs', Functions::class))
            ->addFunction(new StringFunction('ceiling', Functions::class))
            ->addFunction(new StringFunction('floor', Functions::class))
            ->addFunction(new StringFunction('round', Functions::class))
            ->addFunction(new StringFunction('roundHalfToEven', Functions::class))
            ->addFunction(new StringFunction('startsWith', Functions::class))
            ->addFunction(new StringFunction('endsWith', Functions::class))
            ->addFunction(new StringFunction('indexOf', Functions::class))
            ->addFunction(new StringFunction('matches', Functions::class))
            ->addFunction(new StringFunction('lowerCase', Functions::class))
            ->addFunction(new StringFunction('upperCase', Functions::class))
            ->addFunction(new StringFunction('tokenize', Functions::class))
            ->addFunction(new StringFunction('translate', Functions::class))
            ->addFunction(new StringFunction('substringAfter', Functions::class))
            ->addFunction(new StringFunction('substringBefore', Functions::class))
            ->addFunction(new StringFunction('replace', Functions::class))
        ;
    }

    /**
     * @param FunctionInterface $function
     * @return $this
     */
    public function addFunction(FunctionInterface $function)
    {
        $this->functions[$function->getXpathMethod()] = $function;
        return $this;
    }

    /**
     * @param $xpathExpression
     * @return string
     */
    public function compile ($xpathExpression) {
        $resultTokens = [];
        $lexer = Lexer::tokenize($xpathExpression);
        foreach ($lexer as $token) {
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