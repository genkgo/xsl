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
        return XsDate::fromDateTime(DateTimeImmutable::createFromFormat(XsDate::FORMAT, $value));
    }

    /**
     * @param $value
     * @return XsTime
     */
    public static function xsTime($value)
    {
        return XsTime::fromDateTime(DateTimeImmutable::createFromFormat(XsTime::FORMAT, $value));
    }

    /**
     * @param $value
     * @return XsDateTime
     */
    public static function xsDateTime($value)
    {
        return XsDateTime::fromDateTime(DateTimeImmutable::createFromFormat(XsDateTime::FORMAT, $value));
    }
}
