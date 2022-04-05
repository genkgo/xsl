<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Node;

use DOMElement;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;
use Genkgo\Xsl\Xsl\XslTransformations;

final class ElementFunction implements ElementTransformerInterface
{
    /**
     * @param DOMElement $element
     * @return bool
     */
    public function supports(DOMElement $element): bool
    {
        return $element->ownerDocument->documentElement->getAttribute('version') !== '1.0' && $element->localName === 'function';
    }

    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element): void
    {
        $prefixes = $element->ownerDocument->documentElement->getAttribute('extension-element-prefixes');
        if (\strpos(' ' . $prefixes . ' ', ' func ') === false) {
            $prefixes .=  ' func';
        }

        $element->ownerDocument->documentElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:func', 'http://exslt.org/functions');
        $element->ownerDocument->documentElement->setAttribute('extension-element-prefixes', \trim($prefixes));

        $exsltFunction = $element->ownerDocument->createElementNS('http://exslt.org/functions', 'function');
        $exsltFunction->setAttribute('name', $element->getAttribute('name'));

        $appendTo = $exsltFunction;
        $capture = false;
        while ($element->childNodes->length > 0) {
            $item = $element->childNodes->item(0);

            if ($capture === false && $item instanceof DOMElement && $item->nodeName !== 'xsl:param') {
                $resultVariable = $element->ownerDocument->createElementNS(XslTransformations::URI, 'xsl:variable');
                $resultVariable->setAttribute('name', 'result-function');
                $appendTo->appendChild($resultVariable);
                $appendTo = $resultVariable;

                $resultResult = $element->ownerDocument->createElementNS('http://exslt.org/functions', 'func:result');
                $resultResult->setAttribute('select', '$result-function');
                $exsltFunction->appendChild($resultResult);
                $capture = true;
            }

            if ($item !== null) {
                $appendTo->appendChild($item);
            }
        }

        $element->parentNode->replaceChild($exsltFunction, $element);
    }
}
