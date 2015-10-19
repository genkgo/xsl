<?php
namespace Genkgo\Xsl\Xsl\Functions;

use Genkgo\Xsl\Callback\ContextFunction;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\Callback\InvokableInterface;
use Genkgo\Xsl\Xpath\Lexer;
use Genkgo\Xsl\Xsl\Functions;

class CurrentGroupingKey implements FunctionInterface, InvokableInterface
{
    /**
     * @var ContextFunction
     */
    private $parentFunction;

    public function __construct()
    {
        $this->parentFunction =  new ContextFunction('current-grouping-key');
    }

    public function setForElement(\DOMElement $element, $key)
    {
        $element->setAttribute('data-current-grouping-key', $key);
    }

    /**
     * @param Lexer $lexer
     * @return array|\string[]
     */
    public function replace(Lexer $lexer)
    {
        return $this->parentFunction->replace($lexer);
    }

    public function call($arguments)
    {
        $elements = $arguments[1];
        return $elements[0]->getAttribute('data-current-grouping-key');
    }
}
