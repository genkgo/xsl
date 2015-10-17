<?php
namespace Genkgo\Xsl\Xsl\Node;

use DOMDocument;
use DOMElement;
use Genkgo\Xsl\Context;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;

class ElementForEachGroup implements ElementTransformerInterface {

    public function transform(DOMElement $element, Context $context)
    {
        if ($element->nodeName === 'xsl:for-each-group') {
            $select = $element->getAttribute('select');
            $groupBy = $element->getAttribute('group-by');
            $callback = static::class . '::groupBy';

            $element->setAttribute(
                'select',
                'php:function(\'' . $callback . '\', ' . $select . ', \'' . $groupBy . '\')'
            );
        }
    }

    /**
     * @param DOMDocument[] $elements
     * @param $separator
     * @return string
     */
    public static function groupBy($elements, $separator)
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