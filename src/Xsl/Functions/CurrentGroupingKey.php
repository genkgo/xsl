<?php
namespace Genkgo\Xsl\Xsl\Functions;

use DOMElement;
use DOMNode;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\Callback\ReplaceFunctionInterface;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xpath\Lexer;
use Genkgo\Xsl\Xsl\Functions;

/**
 * Class CurrentGroupingKey
 * @package Genkgo\Xsl\Xsl\Functions
 */
class CurrentGroupingKey implements ReplaceFunctionInterface, FunctionInterface
{
    /**
     * @param FunctionMap $functionMap
     */
    public function register(FunctionMap $functionMap)
    {
        $functionMap->set('current-grouping-key', $this);
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array|\string[]
     */
    public function replace(Lexer $lexer, DOMNode $currentElement)
    {
        $groupId = null;

        /** @var DOMElement $xslForEach */
        $xslForEach = $currentElement->parentNode;
        while ($this->isForEachGroupElement($xslForEach) === false && $currentElement->ownerDocument !== $xslForEach) {
            $xslForEach = $xslForEach->parentNode;
        }

        if ($this->isForEachGroupElement($xslForEach) === false) {
            throw new \RuntimeException('Feature not implemented. "At other times, it will be an empty sequence."');
        }

        $groupId = $xslForEach->getAttribute('group-id');

        $resultTokens = [];
        $resultTokens[] = '$current-group-' . $groupId;
        $resultTokens[] = '/';
        $resultTokens[] = '@key';

        $lexer->seek($lexer->key() + 2);
        return $resultTokens;
    }

    /**
     * @param DOMElement $element
     * @return bool
     */
    private function isForEachGroupElement(DOMElement $element)
    {
        return $element->nodeName === 'xsl:for-each' && $element->getAttribute('group-id');
    }
}
