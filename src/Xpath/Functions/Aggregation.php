<?php
namespace Genkgo\Xsl\Xpath\Functions;

use DOMElement;

/**
 * Class Aggregation
 * @package Genkgo\Xsl\Xpath\Functions
 */
trait Aggregation
{
    /**
     * @param DOMElement[] $elements
     * @return float
     */
    public static function avg($elements)
    {
        $total = 0;

        foreach ($elements as $element) {
            $total += (int) $element->nodeValue;
        }

        return $total / count($elements);
    }

    /**
     * @param DOMElement[] $elements
     * @return number
     */
    public static function max($elements)
    {
        $items = [];

        foreach ($elements as $element) {
            $items[] = $element->nodeValue;
        }

        return max($items);
    }

    /**
     * @param DOMElement[] $elements
     * @return number
     */
    public static function min($elements)
    {
        $items = [];

        foreach ($elements as $element) {
            $items[] = $element->nodeValue;
        }

        return min($items);
    }
}
