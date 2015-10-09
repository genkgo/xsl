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

    public static function matches ($input, $pattern, $flags = '') {
        return preg_match('/' . $pattern . '/' . $flags, $input) === 1;
    }

    public static function lowerCase ($input) {
        return strtolower($input);
    }

    public static function upperCase ($input) {
        return strtoupper($input);
    }

    public static function translate ($input, $mapString, $translateString) {
        return strtr($input, $mapString, $translateString);
    }

    public static function substringAfter ($haystack, $needle) {
        if ($needle === '') {
            return $haystack;
        }

        $position = strpos($haystack, $needle);
        if ($position === false) {
            return '';
        }

        return substr($haystack, $position + strlen($needle));
    }

    public static function substringBefore ($haystack, $needle) {
        if ($needle === '') {
            return $haystack;
        }

        $position = strpos($haystack, $needle);
        if ($position === false) {
            return '';
        }

        return substr($haystack, 0, $position);
    }

    public static function tokenize ($input, $pattern, $flags = '') {
        $resultSet = new \DOMDocument();
        $resultSet->appendChild($resultSet->createElement('resultSet'));

        $matches = preg_split('/' . $pattern . '/' . $flags, $input);
        foreach ($matches as $match) {
            $result = $resultSet->createElement('result', $match);
            $resultSet->documentElement->appendChild($result);
        }

        return $resultSet;
    }

}