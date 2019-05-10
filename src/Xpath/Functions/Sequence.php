<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath\Functions;

use Genkgo\Xsl\Callback\Arguments;
use Genkgo\Xsl\Schema\XsSequence;

final class Sequence
{
    /**
     * @param Arguments $arguments
     * @return XsSequence
     */
    public static function reverse(Arguments $arguments): XsSequence
    {
        return XsSequence::fromArray(\array_reverse($arguments->castAsSequence(0)));
    }

    /**
     * @param Arguments $arguments
     * @return XsSequence
     */
    public static function insertBefore(Arguments $arguments): XsSequence
    {
        $elements = $arguments->castAsSequence(0);

        \array_splice(
            $elements,
            (int)$arguments->castFromSchemaType(1) - 1,
            0,
            $arguments->get(2)
        );

        return XsSequence::fromArray($elements);
    }

    /**
     * @param Arguments $arguments
     * @return XsSequence
     */
    public static function remove(Arguments $arguments): XsSequence
    {
        $elements = $arguments->castAsSequence(0);

        unset($elements[(int)$arguments->castFromSchemaType(1) - 1]);
        return XsSequence::fromArray($elements);
    }

    /**
     * @param Arguments $arguments
     * @return XsSequence
     */
    public static function subsequence(Arguments $arguments): XsSequence
    {
        $elements = $arguments->castAsSequence(0);
        $position = (int)$arguments->castFromSchemaType(1);

        try {
            $length = (int)$arguments->castFromSchemaType(2);
        } catch (\InvalidArgumentException $e) {
            $length = \count($elements);
        }

        return XsSequence::fromArray(\array_slice($elements, (int)$position - 1, (int)$length));
    }

    /**
     * @param Arguments $arguments
     * @return XsSequence
     */
    public static function distinctValues(Arguments $arguments): XsSequence
    {
        $sequence = XsSequence::fromArray($arguments->castAsSequence(0));

        $values = [];
        foreach ($sequence->documentElement->childNodes as $childNode) {
            $values[] = $childNode->nodeValue;
        }

        return XsSequence::fromArray(\array_unique($values));
    }

    /**
     * @param Arguments $arguments
     * @return XsSequence
     */
    public static function unordered(Arguments $arguments): XsSequence
    {
        $elements = $arguments->castAsSequence(0);
        \shuffle($elements);

        return XsSequence::fromArray($elements);
    }

    /**
     * @param Arguments $arguments
     * @return bool|int
     */
    public static function indexOf(Arguments $arguments)
    {
        $haystack = $arguments->get(0);
        $needle = $arguments->get(1);

        if (\is_string($haystack)) {
            $position = \strpos($haystack, $needle);
            if ($position === false) {
                return false;
            }

            return $position + 1;
        }

        $index = 1;
        $sequence = XsSequence::fromArray($arguments->castAsSequence(0));

        foreach ($sequence->documentElement->childNodes as $childNode) {
            if ($needle === $childNode->nodeValue) {
                return $index;
            }

            $index++;
        }

        return false;
    }
}
