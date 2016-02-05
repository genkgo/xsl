<?php
namespace Genkgo\Xsl\Xsl\Functions;

use DateTimeImmutable;
use DOMElement;
use Genkgo\Xsl\Schema\XsDate;
use Genkgo\Xsl\Schema\XsDateTime;
use Genkgo\Xsl\Schema\XsTime;
use Genkgo\Xsl\Util\Assert;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;
use Genkgo\Xsl\Xsl\Functions;

/**
 * Class DateFormatter
 * @package Genkgo\Xsl\Xsl\Functions
 */
class DateFormatting
{
    /**
     * @var Functions\Formatter\DateTimeFormatter
     */
    private static $dateTimeFormatter;
    /**
     * @var Functions\Formatter\DateTimeFormatter
     */
    private static $dateFormatter;
    /**
     * @var Functions\Formatter\DateTimeFormatter
     */
    private static $timeFormatter;

    /**
     * @param DOMElement[] $value
     * @param $picture
     * @param null $language
     * @param null $calendar
     * @param null $country
     * @return string
     * @throws InvalidArgumentException
     */
    public static function formatDate($value, $picture, $language = null, $calendar = null, $country = null)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'date');

        if (self::$dateFormatter === null) {
            self::$dateFormatter = Functions\Formatter\DateTimeFormatter::createWithFlagDate();
        }

        if ($language === null) {
            $language = 'en';
        }

        if ($country === null) {
            $country = 'US';
        }

        $date = DateTimeImmutable::createFromFormat(XsDate::FORMAT, $value[0]->nodeValue);
        return self::$dateFormatter->format($date, $picture, $language, 'AD', $country);
    }

    /**
     * @param DOMElement[] $value
     * @param string $picture
     * @param null $language
     * @param null $calendar
     * @param null $country
     * @return string
     * @throws InvalidArgumentException
     */
    public static function formatTime($value, $picture, $language = null, $calendar = null, $country = null)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'time');

        if (self::$timeFormatter === null) {
            self::$timeFormatter = Functions\Formatter\DateTimeFormatter::createWithFlagTime();
        }

        if ($language === null) {
            $language = 'en';
        }

        if ($country === null) {
            $country = 'US';
        }

        $date = DateTimeImmutable::createFromFormat(XsTime::FORMAT, $value[0]->nodeValue);
        return self::$timeFormatter->format($date, $picture, $language, 'AD', $country);
    }

    /**
     * @param DOMElement[] $value
     * @param string $picture
     * @param null $language
     * @param null $calendar
     * @param null $country
     * @return string
     * @throws InvalidArgumentException
     */
    public static function formatDateTime($value, $picture, $language = null, $calendar = null, $country = null)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dateTime');

        if (self::$dateTimeFormatter === null) {
            self::$dateTimeFormatter = Functions\Formatter\DateTimeFormatter::createWithFlagDateTime();
        }

        if ($language === null) {
            $language = 'en';
        }

        if ($country === null) {
            $country = 'US';
        }

        $date = DateTimeImmutable::createFromFormat(XsDateTime::FORMAT, $value[0]->nodeValue);
        return self::$dateTimeFormatter->format($date, $picture, $language, 'AD', $country);
    }
}
