<?php
namespace Genkgo\Xsl\Xsl;

use DOMDocument;

class Elements {

    /**
     * @param DOMDocument[] $elements
     * @param $separator
     * @return string
     */
    public static function valueOfSeparate ($elements, $separator) {
        $result = '';

        $index = 0;
        foreach ($elements as $element) {
            foreach ($element->documentElement->childNodes as $node) {
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