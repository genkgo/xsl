<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath\Functions;

use DOMElement;
use Genkgo\Xsl\Schema\XsSequence;

final class Aggregation
{
    /**
     * @param DOMElement[] $elements
     * @return float|XsSequence
     */
    public static function avg($elements)
    {
        if (\count($elements) === 0) {
            return XsSequence::fromArray([]);
        }

        $total = 0;

        foreach ($elements as $element) {
            $total += (int) $element->nodeValue;
        }

        return $total / \count($elements);
    }

    /**
     * @param DOMElement[] $elements
     * @return mixed
     */
    public static function max($elements)
    {
        if (\count($elements) === 0) {
            return XsSequence::fromArray([]);
        }

        $items = [];

        foreach ($elements as $element) {
            $items[] = $element->nodeValue;
        }

        return \max($items);
    }

    /**
     * @param DOMElement[] $elements
     * @return mixed
     */
    public static function min($elements)
    {
        if (\count($elements) === 0) {
            return XsSequence::fromArray([]);
        }

        $items = [];

        foreach ($elements as $element) {
            $items[] = $element->nodeValue;
        }

        return \min($items);
    }
}
