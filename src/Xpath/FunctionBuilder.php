<?php
namespace Genkgo\Xsl\Xpath;

/**
 * Class FunctionBuilder
 * @package Genkgo\Xsl\Xpath
 */
class FunctionBuilder
{
    /**
     * @var
     */
    private $name;
    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param $argument
     * @return $this
     */
    public function addArgument($argument)
    {
        $this->arguments[] = [$argument, true];
        return $this;
    }

    /**
     * @param $expression
     * @return $this
     */
    public function addExpression($expression)
    {
        $this->arguments[] = [$expression, false];
        return $this;
    }

    /**
     * @return string
     */
    public function build()
    {
        $argumentList = [];
        foreach ($this->arguments as $argumentSettings) {
            list($argument, $quote) = $argumentSettings;

            if ($quote) {
                $argumentList[] = '\'' . $argument . '\'';
            } else {
                $argumentList[] = $argument;
            }
        }

        return $this->name . '(' . implode(',', $argumentList) . ')';
    }
}
