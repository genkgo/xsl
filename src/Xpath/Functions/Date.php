<?php
namespace Genkgo\Xsl\Xpath\Functions;

use DateTimeImmutable;
use Genkgo\Xsl\Schema\XsDate;
use Genkgo\Xsl\Schema\XsDateTime;
use Genkgo\Xsl\Schema\XsInteger;
use Genkgo\Xsl\Schema\XsTime;
use Genkgo\Xsl\Util\Assert;

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

    /**
     * @param $value
     * @return XsInteger
     * @throws \Genkgo\Xsl\Xpath\Exception\InvalidArgumentException
     */
    public static function yearFromDate($value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'date');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('Y'));
    }

    /**
     * @param $value
     * @return XsInteger
     * @throws \Genkgo\Xsl\Xpath\Exception\InvalidArgumentException
     */
    public static function yearFromDateTime($value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dateTime');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('Y'));
    }

    /**
     * @param $value
     * @return XsInteger
     * @throws \Genkgo\Xsl\Xpath\Exception\InvalidArgumentException
     */
    public static function monthFromDate($value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'date');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('n'));
    }

    /**
     * @param $value
     * @return XsInteger
     * @throws \Genkgo\Xsl\Xpath\Exception\InvalidArgumentException
     */
    public static function monthFromDateTime($value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dateTime');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('n'));
    }

    /**
     * @param $value
     * @return XsInteger
     * @throws \Genkgo\Xsl\Xpath\Exception\InvalidArgumentException
     */
    public static function dayFromDate($value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'date');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('j'));
    }

    /**
     * @param $value
     * @return XsInteger
     * @throws \Genkgo\Xsl\Xpath\Exception\InvalidArgumentException
     */
    public static function dayFromDateTime($value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dateTime');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('j'));
    }

    /**
     * @param $value
     * @return XsInteger
     * @throws \Genkgo\Xsl\Xpath\Exception\InvalidArgumentException
     */
    public static function hoursFromTime($value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'time');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('G'));
    }

    /**
     * @param $value
     * @return XsInteger
     * @throws \Genkgo\Xsl\Xpath\Exception\InvalidArgumentException
     */
    public static function hoursFromDateTime($value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dateTime');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('G'));
    }

    /**
     * @param $value
     * @return XsInteger
     * @throws \Genkgo\Xsl\Xpath\Exception\InvalidArgumentException
     */
    public static function minutesFromTime($value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'time');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('i'));
    }

    /**
     * @param $value
     * @return XsInteger
     * @throws \Genkgo\Xsl\Xpath\Exception\InvalidArgumentException
     */
    public static function minutesFromDateTime($value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dateTime');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('i'));
    }

    /**
     * @param $value
     * @return XsInteger
     * @throws \Genkgo\Xsl\Xpath\Exception\InvalidArgumentException
     */
    public static function secondsFromTime($value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'time');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('s'));
    }

    /**
     * @param $value
     * @return XsInteger
     * @throws \Genkgo\Xsl\Xpath\Exception\InvalidArgumentException
     */
    public static function secondsFromDateTime($value)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dateTime');

        return new XsInteger((int) (new DateTimeImmutable($value[0]->nodeValue))->format('s'));
    }
}
