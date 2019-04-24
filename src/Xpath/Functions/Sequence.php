<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath\Functions;

use Genkgo\Xsl\Schema\XsSequence;

final class Sequence
{
    /**
     * @param array $elements
     * @return XsSequence
     */
    public static function reverse(array $elements): XsSequence
    {
        return XsSequence::fromArray(\array_reverse($elements));
    }

    /**
     * @param array $elements
     * @param int|float $position
     * @param mixed $element
     * @return XsSequence
     */
    public static function insertBefore(array $elements, $position, $element): XsSequence
    {
        \array_splice($elements, (int)$position - 1, 0, $element);
        return XsSequence::fromArray($elements);
    }

    /**
     * @param array $elements
     * @param int|float $position
     * @return XsSequence
     */
    public static function remove(array $elements, $position): XsSequence
    {
        unset($elements[(int)$position - 1]);
        return XsSequence::fromArray($elements);
    }

    /**
     * @param array $elements
     * @param int|float $position
     * @param int|float|null $length
     * @return XsSequence
     */
    public static function subsequence(array $elements, $position, $length = null): XsSequence
    {
        return XsSequence::fromArray(\array_slice($elements, (int)$position - 1, $length));
    }

    /**
     * @param array $elements
     * @return XsSequence
     */
    public static function distinctValues(array $elements): XsSequence
    {
        $sequence = XsSequence::fromArray($elements);

        $values = [];
        foreach ($sequence->documentElement->childNodes as $childNode) {
            $values[] = $childNode->nodeValue;
        }

        return XsSequence::fromArray(\array_unique($values));
    }

    /**
     * @param array $elements
     * @return XsSequence
     */
    public static function unordered(array $elements): XsSequence
    {
        \shuffle($elements);

        return XsSequence::fromArray($elements);
    }

    /**
     * @param mixed $haystack
     * @param mixed $needle
     * @return bool|int
     */
    public static function indexOf($haystack, $needle)
    {
        if (\is_string($haystack)) {
            $position = \strpos($haystack, $needle);
            if ($position === false) {
                return false;
            }

            return $position + 1;
        }

        $index = 1;
        $sequence = XsSequence::fromArray($haystack);

        foreach ($sequence->documentElement->childNodes as $childNode) {
            if ($needle === $childNode->nodeValue) {
                return $index;
            }

            $index++;
        }

        return false;
    }
}
