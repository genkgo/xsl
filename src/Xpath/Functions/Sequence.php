<?php
namespace Genkgo\Xsl\Xpath\Functions;

use Genkgo\Xsl\Schema\XsSequence;

/**
 * Class Sequence
 * @package Genkgo\Xsl\Xpath\Functions
 */
trait Sequence
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
}
