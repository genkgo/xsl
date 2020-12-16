<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath\Functions;

final class Math
{
    /**
     * @param int|float $number
     * @return int|float
     */
    public static function abs($number)
    {
        return \abs((float) $number);
    }

    /**
     * @param int|float $number
     * @return int|float
     */
    public static function ceiling($number)
    {
        return \ceil((float) $number);
    }

    /**
     * @param int|float $number
     * @return int|float
     */
    public static function floor($number)
    {
        return \floor((float) $number);
    }

    /**
     * @param int|float $number
     * @return int|float
     */
    public static function round($number)
    {
        return \round((float) $number);
    }

    /**
     * @param int|float $number
     * @param int $precision
     * @return int|float
     */
    public static function roundHalfToEven($number, $precision = 0)
    {
        return \round((float) $number, (int)$precision, PHP_ROUND_HALF_EVEN);
    }
}
