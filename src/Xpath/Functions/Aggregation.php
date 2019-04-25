<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath\Functions;

use Genkgo\Xsl\Callback\Arguments;
use Genkgo\Xsl\Schema\XsSequence;

final class Aggregation
{
    /**
     * @param Arguments $arguments
     * @return float|int|XsSequence
     */
    public static function avg(Arguments $arguments)
    {
        $elements = $arguments->castAsSequence(0);

        if (\count($elements) === 0) {
            return XsSequence::fromArray([]);
        }

        return \array_sum($elements) / \count($elements);
    }

    /**
     * @param Arguments $arguments
     * @return mixed
     */
    public static function max(Arguments $arguments)
    {
        $elements = $arguments->castAsSequence(0);

        if (\count($elements) === 0) {
            return XsSequence::fromArray([]);
        }

        return \max($elements);
    }

    /**
     * @param Arguments $arguments
     * @return mixed
     */
    public static function min(Arguments $arguments)
    {
        $elements = $arguments->castAsSequence(0);

        if (\count($elements) === 0) {
            return XsSequence::fromArray([]);
        }

        return \min($elements);
    }
}
