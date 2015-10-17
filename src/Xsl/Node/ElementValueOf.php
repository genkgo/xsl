<?php
namespace Genkgo\Xsl\Xsl\Node;

use DOMDocument;
use DOMElement;
use Genkgo\Xsl\DocumentContext;
use Genkgo\Xsl\PhpCallback;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;

class ElementValueOf implements ElementTransformerInterface {

    public function transform(DOMElement $element, DocumentContext $context)
    {
        if ($element->nodeName === 'xsl:value-of' && $element->hasAttribute('separator')) {
            $select = $element->getAttribute('select');
            $separator = $element->getAttribute('separator');
            $callback = static::class;

            $element->setAttribute(
                'select',
                'php:function(\'' . PhpCallback::class . '::call\',\'' . $callback . '\',\'valueOfSeparate\', ' . $select . ', \'' . $separator . '\')'
            );
        }
    }

    /**
     * @param DOMDocument[] $elements
     * @param $separator
     * @return string
     */
    public static function valueOfSeparate($elements, $separator)
    {
        $result = '';

        $index = 0;
        foreach ($elements as $sequence) {
            $itemsXpath = new \DOMXPath($sequence);
            $items = $itemsXpath->query('xs:*');

            foreach ($items as $node) {
                if ($index > 0) {
                    $result .= $separator;
                }

                $result .= $node->nodeValue;
                $index++;
            }
        }

        return $result;
    }
}