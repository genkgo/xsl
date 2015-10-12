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
        $this->addFunction(new StringFunction('abs', Functions::class));
        $this->addFunction(new StringFunction('ceiling', Functions::class));
        $this->addFunction(new StringFunction('floor', Functions::class));
        $this->addFunction(new StringFunction('round', Functions::class));
        $this->addFunction(new StringFunction('roundHalfToEven', Functions::class));
        $this->addFunction(new StringFunction('startsWith', Functions::class));
        $this->addFunction(new StringFunction('endsWith', Functions::class));
        $this->addFunction(new StringFunction('indexOf', Functions::class));
        $this->addFunction(new StringFunction('matches', Functions::class));
        $this->addFunction(new StringFunction('lowerCase', Functions::class));
        $this->addFunction(new StringFunction('upperCase', Functions::class));
        $this->addFunction(new StringFunction('tokenize', Functions::class));
        $this->addFunction(new StringFunction('translate', Functions::class));
        $this->addFunction(new StringFunction('substringAfter', Functions::class));
        $this->addFunction(new StringFunction('substringBefore', Functions::class));
        $this->addFunction(new StringFunction('replace', Functions::class));
    }

    /**
     * @param FunctionInterface $function
     */
    public function addFunction(FunctionInterface $function)
    {
        $this->functions[$function->getXpathMethod()] = $function;
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