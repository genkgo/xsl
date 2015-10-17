<?php
namespace Genkgo\Xsl\Xpath;

class FunctionBuilder {

    private $name;
    private $arguments = [];

    public function __construct ($name) {
        $this->name = $name;
    }

    public function addArgument ($argument, $quote = true) {
        $this->arguments[] = [$argument, $quote];
        return $this;
    }

    public function build() {
        $argumentList = [];
        foreach ($this->arguments as $argumentSettings) {
            list ($argument, $quote) = $argumentSettings;

            if ($quote) {
                $argumentList[] = '\'' . $argument . '\'';
            } else {
                $argumentList[] = $argument;
            }
        }

        return $this->name . '(' . implode(',', $argumentList) . ')';
    }

}