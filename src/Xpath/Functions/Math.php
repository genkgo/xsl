<?php
namespace Genkgo\Xsl\Xpath\Functions;

/**
 * Class Math
 * @package Genkgo\Xsl\Xpath\Functions
 */
trait Math
{
    /**
     * @param $number
     * @return number
     */
    public static function abs($number)
    {
        return abs($number);
    }

    /**
     * @param $number
     * @return float
     */
    public static function ceiling($number)
    {
        return ceil($number);
    }

    /**
     * @param $number
     * @return float
     */
    public static function floor($number)
    {
        return floor($number);
    }

    /**
     * @param $number
     * @return float
     */
    public static function round($number)
    {
        return round($number);
    }

    /**
     * @param $number
     * @param int $precision
     * @return float
     */
    public static function roundHalfToEven($number, $precision = 0)
    {
        return round($number, $precision, PHP_ROUND_HALF_EVEN);
    }
}
