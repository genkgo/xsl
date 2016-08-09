<?php
namespace Genkgo\Xsl\Xpath\Functions;

use Genkgo\Xsl\Schema\XsSequence;

/**
 * Class Sequence
 * @package Genkgo\Xsl\Xpath\Functions
 */
class Sequence
{
    /**
     * @param $elements
     * @return XsSequence
     * @throws \Genkgo\Xsl\Schema\Exception\UnknownSequenceItemException
     */
    public static function reverse($elements)
    {
        return XsSequence::fromArray(array_reverse($elements));
    }

    /**
     * @param $elements
     * @param $position
     * @param $element
     * @return XsSequence
     */
    public static function insertBefore($elements, $position, $element)
    {
        array_splice($elements, $position - 1, 0, $element);
        return XsSequence::fromArray($elements);
    }

    /**
     * @param $elements
     * @param $position
     * @return XsSequence
     */
    public static function remove($elements, $position)
    {
        unset($elements[$position - 1]);
        return XsSequence::fromArray($elements);
    }

    /**
     * @param $elements
     * @param $position
     * @param null $length
     * @return XsSequence
     * @throws \Genkgo\Xsl\Schema\Exception\UnknownSequenceItemException
     */
    public static function subsequence($elements, $position, $length = null)
    {
        return XsSequence::fromArray(array_slice($elements, $position - 1, $length));
    }

    /**
     * @param $elements
     * @return XsSequence
     * @throws \Genkgo\Xsl\Schema\Exception\UnknownSequenceItemException
     */
    public static function distinctValues($elements)
    {
        $sequence = XsSequence::fromArray($elements);

        $values = [];
        foreach ($sequence->documentElement->childNodes as $childNode) {
            $values[] = $childNode->nodeValue;
        }

        return XsSequence::fromArray(array_unique($values));
    }

    /**
     * @param $elements
     * @return XsSequence
     * @throws \Genkgo\Xsl\Schema\Exception\UnknownSequenceItemException
     */
    public static function unordered($elements)
    {
        shuffle($elements);

        return XsSequence::fromArray($elements);
    }

    /**
     * @param $haystack
     * @param $needle
     * @return bool|int
     */
    public static function indexOf($haystack, $needle)
    {
        if (is_string($haystack)) {
            $position = strpos($haystack, $needle);
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
