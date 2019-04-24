<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath;

final class FunctionBuilder
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $argument
     * @return $this
     */
    public function addArgument($argument): self
    {
        $this->arguments[] = [$argument, true];
        return $this;
    }

    /**
     * @param mixed $expression
     * @return $this
     */
    public function addExpression($expression): self
    {
        $this->arguments[] = [$expression, false];
        return $this;
    }

    /**
     * @return string
     */
    public function build(): string
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

        return $this->name . '(' . \implode(',', $argumentList) . ')';
    }
}
