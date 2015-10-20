<?php
namespace Genkgo\Xsl\Schema;

use DateTimeImmutable;

/**
 * Class Functions
 * @package Genkgo\Xsl\Schema
 */
class Functions
{
    /**
     * @param $value
     * @return XsDate
     */
    public static function xsDate($value)
    {
        $value = XsDate::castToNodeValue($value);
        return XsDate::fromString($value);
    }

    /**
     * @param $value
     * @return XsTime
     */
    public static function xsTime($value)
    {
        $value = XsTime::castToNodeValue($value);
        return XsTime::fromString($value);
    }

    /**
     * @param $value
     * @return XsDateTime
     */
    public static function xsDateTime($value)
    {
        $value = XsDateTime::castToNodeValue($value);
        return XsDateTime::fromString($value);
    }
}
