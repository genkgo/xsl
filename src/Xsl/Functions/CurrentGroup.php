<?php
namespace Genkgo\Xsl\Xsl\Functions;

use Genkgo\Xsl\Callback\ContextFunction;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\Callback\InvokableInterface;
use Genkgo\Xsl\Callback\ReturnXsSequenceFunction;
use Genkgo\Xsl\Schema\XsSequence;
use Genkgo\Xsl\Xpath\Lexer;
use Genkgo\Xsl\Xsl\Functions;

class CurrentGroup implements FunctionInterface, InvokableInterface
{
    private $parentFunction;

    private $groups;

    public function __construct()
    {
        $this->parentFunction =  new ReturnXsSequenceFunction(new ContextFunction('current-group'));
    }

    public function setForElement(\DOMElement $element, $group)
    {
        $objectHash = spl_object_hash($element);
        $element->setAttribute('data-current-group-hash', $objectHash);
        $this->groups[$objectHash] = $group;
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
        $objectHash = $elements[0]->getAttribute('data-current-group-hash');
        return XsSequence::fromArray($this->groups[$objectHash]);
    }
}
