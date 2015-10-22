<?php
namespace Genkgo\Xsl\Xsl\Functions;

use Genkgo\Xsl\Callback\ContextFunction;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\Callback\MethodCallInterface;
use Genkgo\Xsl\Callback\ReplaceFunctionInterface;
use Genkgo\Xsl\Callback\ReturnXsSequenceFunction;
use Genkgo\Xsl\Schema\XsSequence;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xpath\Lexer;
use Genkgo\Xsl\Xsl\Functions;

/**
 * Class CurrentGroupingKey
 * @package Genkgo\Xsl\Xsl\Functions
 */
class CurrentGroup implements ReplaceFunctionInterface, FunctionInterface, MethodCallInterface
{
    const NAME = 'current-group';

    /**
     * @var ContextFunction
     */
    private $replacer;
    /**
     * @var array
     */
    private $groups = [];

    /**
     *
     */
    public function __construct()
    {
        $this->replacer = new ReturnXsSequenceFunction(new ContextFunction(self::NAME));
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
     * @param $group
     */
    public function setForElement(\DOMElement $element, $group)
    {
        $objectHash = spl_object_hash($element);
        $element->setAttribute('data-current-group-hash', $objectHash);
        $this->groups[$objectHash] = $group;
    }

    /**
     * @param $arguments
     * @param TransformationContext $context
     * @return XsSequence
     * @throws \Genkgo\Xsl\Schema\Exception\UnknownSequenceItemException
     */
    public function call($arguments, TransformationContext $context)
    {
        $elements = $arguments[0];
        $objectHash = $elements[0]->getAttribute('data-current-group-hash');
        return XsSequence::fromArray($this->groups[$objectHash]);
    }
}
