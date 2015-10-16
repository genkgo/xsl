<?php
namespace Genkgo\Xsl\Xpath\Functions;

use Genkgo\Xsl\Schema\XsSequence;

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
     *
     * @author Salman A
     * @link http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
     */
    public static function startsWith($haystack, $needle)
    {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }
    /**
     * @param $haystack
     * @param $needle
     * @return bool
     *
     * @author Salman A
     * @link http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
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
        return XsSequence::fromArray(preg_split('/'.$pattern.'/'.$flags, $input));
    }

    /**
     * @param $input
     * @param $pattern
     * @param string $flags
     * @return \DOMDocument
     */
    public static function replace($input, $pattern, $replacement, $flags = '')
    {
        $resultSet = new \DOMDocument();
        $resultSet->appendChild($resultSet->createElement('resultSet'));

        return preg_replace('/'.$pattern.'/'.$flags, $replacement, $input);
    }

    public static function stringJoin($elements, $separator)
    {
        $result = '';

        $index = 0;
        foreach ($elements as $sequence) {
            foreach ($sequence->documentElement->childNodes as $node) {
                if ($index > 0) {
                    $result .= $separator;
                }

                $result .= $node->nodeValue;
                $index++;
            }
        }

        return $result;
    }
}
