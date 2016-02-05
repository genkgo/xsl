<?php
namespace Genkgo\Xsl\Xsl\Functions;

use DateTime;
use DOMElement;
use Genkgo\Xsl\Schema\XsDate;
use Genkgo\Xsl\Schema\XsDateTime;
use Genkgo\Xsl\Schema\XsTime;
use Genkgo\Xsl\Util\Assert;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;
use Genkgo\Xsl\Xsl\Functions;
use Locale;

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
     * @var array
     */
    private static $locale;

    /**
     * @param DOMElement[] $value
     * @param $picture
     * @param null $language
     * @param null $calendar
     * @return string
     * @throws InvalidArgumentException
     */
    public static function formatDate($value, $picture, $language = null, $calendar = null)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'date');

        if (self::$dateFormatter === null) {
            if (extension_loaded('intl')) {
                self::$dateFormatter = Functions\Formatter\IntlDateTimeFormatter::createWithFlagDate();
            } else {
                self::$dateFormatter = Functions\Formatter\DateTimeFormatter::createWithFlagDate();
            }
        }

        if ($language === null) {
            $locale = self::detectSystemLocale();
        } else {
            $locale = $language;
        }

        $date = DateTime::createFromFormat(XsDate::FORMAT, $value[0]->nodeValue);
        return self::$dateFormatter->format($date, $picture, $locale, 'AD');
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
            if (extension_loaded('intl')) {
                self::$timeFormatter = Functions\Formatter\IntlDateTimeFormatter::createWithFlagTime();
            } else {
                self::$timeFormatter = Functions\Formatter\DateTimeFormatter::createWithFlagTime();
            }
        }

        if ($language === null) {
            $locale = self::detectSystemLocale();
        } else {
            $locale = $language;
        }

        $date = DateTime::createFromFormat(XsTime::FORMAT, $value[0]->nodeValue);
        return self::$timeFormatter->format($date, $picture, $locale, 'AD');
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
            if (extension_loaded('intl')) {
                self::$dateTimeFormatter = Functions\Formatter\IntlDateTimeFormatter::createWithFlagDateTime();
            } else {
                self::$dateTimeFormatter = Functions\Formatter\DateTimeFormatter::createWithFlagDateTime();
            }
        }

        if ($language === null) {
            $locale = self::detectSystemLocale();
        } else {
            $locale = $language;
        }

        $date = DateTime::createFromFormat(XsDateTime::FORMAT, $value[0]->nodeValue);
        return self::$dateTimeFormatter->format($date, $picture, $locale, 'AD');
    }

    private static function detectSystemLocale()
    {
        if (self::$locale === null) {
            self::$locale = self::getSystemLocale();
        }

        return self::$locale;
    }

    /**
     * @return string
     */
    private static function getSystemLocale()
    {
        if (class_exists(Locale::class)) {
            return Locale::getDefault();
        }

        return setlocale(LC_ALL, 0);
    }
}
