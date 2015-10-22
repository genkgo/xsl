<?php
namespace Genkgo\Xsl\Xsl\Functions;

use Genkgo\Xsl\Callback\ContextFunction;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\Callback\MethodCallInterface;
use Genkgo\Xsl\Callback\ReplaceFunctionInterface;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xpath\Lexer;
use Genkgo\Xsl\Xsl\Functions;

/**
 * Class CurrentGroupingKey
 * @package Genkgo\Xsl\Xsl\Functions
 */
class CurrentGroupingKey implements ReplaceFunctionInterface, FunctionInterface, MethodCallInterface
{
    const NAME = 'current-grouping-key';

    /**
     * @var ContextFunction
     */
    private $replacer;

    /**
     *
     */
    public function __construct()
    {
        $this->replacer = new ContextFunction(self::NAME);
    }

    /**
     * @param FunctionMap $functionMap
     */
    public function register(FunctionMap $functionMap)
    {
        $functionMap->set(self::NAME, $this);
    }

    /**
     * @param Lexer $lexer
     * @return array|string[]
     */
    public function replace(Lexer $lexer)
    {
        return $this->replacer->replace($lexer);
    }

    /**
     * @param \DOMElement $element
     * @param $key
     */
    public function setForElement(\DOMElement $element, $key)
    {
        $element->setAttribute('data-current-grouping-key', $key);
    }

    /**
     * @param $arguments
     * @param TransformationContext $context
     * @return mixed
     */
    public function call($arguments, TransformationContext $context)
    {
        $elements = $arguments[0];
        return $elements[0]->getAttribute('data-current-grouping-key');
    }
}
