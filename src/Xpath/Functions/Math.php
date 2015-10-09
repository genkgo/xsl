<?php
namespace Genkgo\Xsl\Xpath\Functions;

trait Math {

    public static function abs ($number) {
        return abs($number);
    }

    public static function ceiling ($number) {
        return ceil($number);
    }

    public static function floor ($number) {
        return floor($number);
    }

    public static function round ($number) {
        return round($number);
    }

    public static function roundHalfToEven ($number, $precision = 0) {
        return round($number, $precision, PHP_ROUND_HALF_EVEN);
    }

}