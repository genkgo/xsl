<?php
namespace Genkgo\Xsl\Schema;

use DateTimeImmutable;
use Genkgo\Xsl\ObjectFunction;

/**
 * Class Functions
 * @package Genkgo\Xsl\Schema
 */
class Functions
{
    /**
     * @return array
     */
    public static function supportedFunctions()
    {
        return [
            new ObjectFunction('xsDate', static::class, 'date'),
            new ObjectFunction('xsTime', static::class, 'time'),
            new ObjectFunction('xsDateTime', static::class, 'dateTime')
        ];
    }

    /**
     * @param $value
     * @return XsDate
     */
    public static function xsDate($value)
    {
        return new XsDate(DateTimeImmutable::createFromFormat(XsDate::FORMAT, $value));
    }

    /**
     * @param $value
     * @return XsTime
     */
    public static function xsTime($value)
    {
        return new XsTime(DateTimeImmutable::createFromFormat(XsTime::FORMAT, $value));
    }

    /**
     * @param $value
     * @return XsDateTime
     */
    public static function xsDateTime($value)
    {
        return new XsDateTime(DateTimeImmutable::createFromFormat(XsDateTime::FORMAT, $value));
    }
}
