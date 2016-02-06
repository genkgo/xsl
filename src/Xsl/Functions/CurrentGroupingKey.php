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
     * @return string[]
     */
    public function replace(Lexer $lexer, DOMNode $currentElement)
    {
        $xslForEach = $currentElement->parentNode;
        if ($xslForEach instanceof DOMNode) {
            while ($this->isForEachGroupElement($xslForEach) === false && $currentElement->ownerDocument !== $xslForEach) {
                $xslForEach = $xslForEach->parentNode;
            }
        }

        if ($this->isForEachGroupElement($xslForEach) === false) {
            $lexer->seek($lexer->key() + 2);
            return ['/', 'xs:sequence', '/', '*'];
        }

        /** @var DOMElement $xslForEach */
        $groupId = $xslForEach->getAttribute('group-id');

        $resultTokens = [];
        $resultTokens[] = '$current-group-' . $groupId;
        $resultTokens[] = '/';
        $resultTokens[] = '@key';

        $lexer->seek($lexer->key() + 2);
        return $resultTokens;
    }

    /**
     * @param DOMNode|DOMElement $element
     * @return bool
     */
    private function isForEachGroupElement(DOMNode $element)
    {
        return $element->nodeName === 'xsl:for-each' && $element->getAttribute('group-id');
    }
}
