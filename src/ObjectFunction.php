<?php
namespace Genkgo\Xsl;

use Genkgo\Xsl\Xpath\Lexer;

class ObjectFunction implements FunctionInterface
{
    private $name;
    private $class;
    private $xpathMethod;

    public function __construct($name, $class, $xpathMethod = null)
    {
        $this->name = $name;
        $this->class = $class;

        if ($xpathMethod === null) {
            $xpathMethod = $this->dasherize($name);
        }

        $this->xpathMethod = $xpathMethod;
    }

    public function getXpathMethod()
    {
        return $this->xpathMethod;
    }

    public function replace(Lexer $lexer)
    {
        $resultTokens = [];
        $resultTokens[] = 'php:function';
        $resultTokens[] = '(';
        $resultTokens[] = '\'';
        $resultTokens[] = $this->class.'::'.$this->name;
        $resultTokens[] = '\'';
        $resultTokens[] = ',';
        $lexer->next();

        return $resultTokens;
    }

    private static function dasherize($methodName)
    {
        if (ctype_lower($methodName) === false) {
            $methodName = strtolower(preg_replace('/(.)(?=[A-Z])/', '$1'.'-', $methodName));
            $methodName = preg_replace('/\s+/', '', $methodName);
        }

        return $methodName;
    }
}
