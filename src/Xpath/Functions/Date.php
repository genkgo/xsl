<?php
namespace Genkgo\Xsl\Xpath\Functions;

use DateTimeImmutable;
use Genkgo\Xsl\Schema\XsDate;
use Genkgo\Xsl\Schema\XsDateTime;
use Genkgo\Xsl\Schema\XsTime;

/**
 * Class Date
 * @package Genkgo\Xsl\Xpath\Functions
 */
class Date
{
    /**
     * @return XsTime
     */
    public static function currentTime()
    {
        return XsTime::now();
    }

    /**
     * @return XsDate
     */
    public static function currentDate()
    {
        return XsDate::now();
    }

    /**
     * @return XsDateTime
     */
    public static function currentDateTime()
    {
        return XsDateTime::now();
    }
}
