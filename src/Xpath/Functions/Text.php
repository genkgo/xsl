<?php
namespace Genkgo\Xsl\Xpath\Functions;

/**
 * Class Text
 * @package Genkgo\Xsl\Xpath\Functions
 */
trait Text
{
    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public static function endsWith($haystack, $needle)
    {
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    /**
     * @param $haystack
     * @param $needle
     * @return bool|int
     */
    public static function indexOf($haystack, $needle)
    {
        $position = strpos($haystack, $needle);
        if ($position === false) {
            return false;
        } else {
            return $position + 1;
        }
    }

    /**
     * @param $input
     * @param $pattern
     * @param string $flags
     * @return bool
     */
    public static function matches($input, $pattern, $flags = '')
    {
        return preg_match('/'.$pattern.'/'.$flags, $input) === 1;
    }

    /**
     * @param $input
     * @return string
     */
    public static function lowerCase($input)
    {
        return strtolower($input);
    }

    /**
     * @param $input
     * @return string
     */
    public static function upperCase($input)
    {
        return strtoupper($input);
    }

    /**
     * @param $input
     * @param $mapString
     * @param $translateString
     * @return string
     */
    public static function translate($input, $mapString, $translateString)
    {
        return strtr($input, $mapString, $translateString);
    }

    /**
     * @param $haystack
     * @param $needle
     * @return string
     */
    public static function substringAfter($haystack, $needle)
    {
        if ($needle === '') {
            return $haystack;
        }

        $position = strpos($haystack, $needle);
        if ($position === false) {
            return '';
        }

        return substr($haystack, $position + strlen($needle));
    }

    /**
     * @param $haystack
     * @param $needle
     * @return string
     */
    public static function substringBefore($haystack, $needle)
    {
        if ($needle === '') {
            return $haystack;
        }

        $position = strpos($haystack, $needle);
        if ($position === false) {
            return '';
        }

        return substr($haystack, 0, $position);
    }

    /**
     * @param $input
     * @param $pattern
     * @param string $flags
     * @return \DOMDocument
     */
    public static function tokenize($input, $pattern, $flags = '')
    {
        $resultSet = new \DOMDocument();
        $resultSet->appendChild($resultSet->createElement('resultSet'));

        $matches = preg_split('/'.$pattern.'/'.$flags, $input);
        foreach ($matches as $match) {
            $result = $resultSet->createElement('result', $match);
            $resultSet->documentElement->appendChild($result);
        }

        return $resultSet;
    }
}
