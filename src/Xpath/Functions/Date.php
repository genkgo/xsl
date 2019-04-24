<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath\Functions;

use DateInterval;
use DateTimeImmutable;
use Genkgo\Xsl\Schema\XsDate;
use Genkgo\Xsl\Schema\XsDateTime;
use Genkgo\Xsl\Schema\XsInteger;
use Genkgo\Xsl\Schema\XsTime;
use Genkgo\Xsl\Util\Assert;

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
     * @param array $value
     * @return XsInteger
     */
    public static function yearFromDate(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'date');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('Y'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function yearFromDateTime(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dateTime');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('Y'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function monthFromDate(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'date');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('n'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function monthFromDateTime(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dateTime');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('n'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function dayFromDate(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'date');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('j'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function dayFromDateTime(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dateTime');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('j'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function hoursFromTime(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'time');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('G'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function hoursFromDateTime(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dateTime');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('G'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function minutesFromTime(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'time');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('i'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function minutesFromDateTime(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dateTime');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('i'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function secondsFromTime(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'time');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('s'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function secondsFromDateTime(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dateTime');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('s'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function yearsFromDuration(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dayTimeDuration');

        return new XsInteger((int) (new DateInterval($value[0]->nodeValue))->format('%y'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function monthsFromDuration(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dayTimeDuration');

        return new XsInteger((int) (new DateInterval($value[0]->nodeValue))->format('%m'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function daysFromDuration(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dayTimeDuration');

        return new XsInteger((int) (new DateInterval($value[0]->nodeValue))->format('%d'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function hoursFromDuration(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dayTimeDuration');

        return new XsInteger((int) (new DateInterval($value[0]->nodeValue))->format('%h'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function minutesFromDuration(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dayTimeDuration');

        return new XsInteger((int) (new DateInterval($value[0]->nodeValue))->format('%i'));
    }

    /**
     * @param array $value
     * @return XsInteger
     */
    public static function secondsFromDuration(array $value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dayTimeDuration');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('%s'));
    }
}
