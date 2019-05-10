<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath\Functions;

use Genkgo\Xsl\Callback\Arguments;
use Genkgo\Xsl\Schema\XsDate;
use Genkgo\Xsl\Schema\XsDateTime;
use Genkgo\Xsl\Schema\XsInteger;
use Genkgo\Xsl\Schema\XsTime;

final class Date
{
    /**
     * @return XsTime
     */
    public static function currentTime(): XsTime
    {
        return XsTime::now();
    }

    /**
     * @return XsDate
     */
    public static function currentDate(): XsDate
    {
        return XsDate::now();
    }

    /**
     * @return XsDateTime
     */
    public static function currentDateTime(): XsDateTime
    {
        return XsDateTime::now();
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function yearFromDate(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'date');
        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('Y'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function yearFromDateTime(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'dateTime');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('Y'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function monthFromDate(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'date');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('n'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function monthFromDateTime(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'dateTime');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('n'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function dayFromDate(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'date');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('j'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function dayFromDateTime(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'dateTime');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('j'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function hoursFromTime(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'time');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('G'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function hoursFromDateTime(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'dateTime');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('G'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function minutesFromTime(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'time');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('i'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function minutesFromDateTime(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'dateTime');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('i'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function secondsFromTime(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'time');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('s'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function secondsFromDateTime(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'dateTime');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('s'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function yearsFromDuration(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'dayTimeDuration');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('%y'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function monthsFromDuration(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'dayTimeDuration');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('%m'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function daysFromDuration(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'dayTimeDuration');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('%d'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function hoursFromDuration(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'dayTimeDuration');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('%h'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function minutesFromDuration(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'dayTimeDuration');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('%i'));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger
     */
    public static function secondsFromDuration(Arguments $arguments)
    {
        $arguments->assertSchemaType(0, 'dayTimeDuration');

        return new XsInteger((int) $arguments->castFromSchemaType(0)->format('%s'));
    }
}
