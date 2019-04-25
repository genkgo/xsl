<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions;

use DOMElement;
use DOMNode;
use Genkgo\Xsl\Callback\Arguments;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Xpath\Lexer;
use Genkgo\Xsl\Xsl\XslTransformations;

final class CurrentGroupingKey implements FunctionInterface
{
    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return string[]
     */
    public function serialize(Lexer $lexer, DOMNode $currentElement): array
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

        if ($currentElement->localName === 'sort' && $currentElement->namespaceURI === XslTransformations::URI) {
            $resultTokens = ['current()', '/', '@key'];
        } else {
            if ($xslForEach instanceof DOMElement) {
                $groupId = $xslForEach->getAttribute('group-id');
                $resultTokens = ['$current-group-' . $groupId, '/', '@key'];
            } else {
                throw new \UnexpectedValueException('Expecting DOMElement');
            }
        }

        $lexer->seek($lexer->key() + 2);
        return $resultTokens;
    }

    /**
     * @param DOMNode|DOMElement $element
     * @return bool
     */
    private function isForEachGroupElement(DOMNode $element)
    {
        if ($element instanceof DOMElement) {
            return $element->nodeName === 'xsl:for-each' && $element->getAttribute('group-id');
        }

        return false;
    }

    /**
     * @param Arguments $arguments
     * @param TransformationContext $context
     * @return mixed
     */
    public function call(Arguments $arguments, TransformationContext $context)
    {
        throw new \BadMethodCallException();
    }
}
