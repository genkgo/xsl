<?php
namespace Genkgo\Xsl\Xpath\Functions;

trait Text {

    public static function endsWith ($haystack, $needle) {
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    public static function indexOf ($haystack, $needle) {
        $position = strpos($haystack, $needle);
        if ($position === false) {
            return false;
        } else {
            return $position + 1;
        }
    }

}