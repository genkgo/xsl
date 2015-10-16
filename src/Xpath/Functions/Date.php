<?php
namespace Genkgo\Xsl\Xpath\Functions;

use DateTimeImmutable;
use Genkgo\Xsl\Schema\XsDate;
use Genkgo\Xsl\Schema\XsDateTime;
use Genkgo\Xsl\Schema\XsTime;

trait Date {

    /**
     * @return XsTime
     */
    public static function currentTime ()
    {
        return new XsTime(new DateTimeImmutable());
    }

    /**
     * @return XsDate
     */
    public static function currentDate ()
    {
        return new XsDate(new DateTimeImmutable());
    }

    /**
     * @return XsDateTime
     */
    public static function currentDateTime ()
    {
        return new XsDateTime(new DateTimeImmutable());
    }

}