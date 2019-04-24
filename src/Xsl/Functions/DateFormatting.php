<?php
declare(strict_types=1);

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

final class DateFormatting
{
    /**
     * @var Functions\Formatter\FormatterInterface
     */
    private static $dateTimeFormatter;

    /**
     * @var Functions\Formatter\FormatterInterface
     */
    private static $dateFormatter;

    /**
     * @var Functions\Formatter\FormatterInterface
     */
    private static $timeFormatter;

    /**
     * @var string
     */
    private static $locale;

    /**
     * @param DOMElement[] $value
     * @param string $picture
     * @param string|null $language
     * @return string
     * @throws InvalidArgumentException
     */
    public static function formatDate($value, string $picture, string $language = null)
    {
        Assert::assertArray($value);
        if (\count($value) === 0) {
            return '';
        }

        Assert::assertSchema($value[0], 'date');

        if (self::$dateFormatter === null) {
            // @codeCoverageIgnoreStart
            if (\extension_loaded('intl')) {
                self::$dateFormatter = Functions\Formatter\IntlDateTimeFormatter::createWithFlagDate();
            } else {
                self::$dateFormatter = Functions\Formatter\DateTimeFormatter::createWithFlagDate();
            }
            // @codeCoverageIgnoreEnd
        }

        if ($language === null) {
            $locale = self::detectSystemLocale();
        } else {
            $locale = $language;
        }

        $date = DateTime::createFromFormat(XsDate::FORMAT, $value[0]->nodeValue);
        if ($date === false) {
            throw new \UnexpectedValueException('Cannot initialize DateTime from ' . $value[0]->nodeValue);
        }

        return self::$dateFormatter->format($date, $picture, $locale, 'AD');
    }

    /**
     * @param DOMElement[] $value
     * @param string $picture
     * @param string|null $language
     * @return string
     * @throws InvalidArgumentException
     */
    public static function formatTime($value, string $picture, string $language = null)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'time');

        if (self::$timeFormatter === null) {
            // @codeCoverageIgnoreStart
            if (\extension_loaded('intl')) {
                self::$timeFormatter = Functions\Formatter\IntlDateTimeFormatter::createWithFlagTime();
            } else {
                self::$timeFormatter = Functions\Formatter\DateTimeFormatter::createWithFlagTime();
            }
            // @codeCoverageIgnoreEnd
        }

        if ($language === null) {
            $locale = self::detectSystemLocale();
        } else {
            $locale = $language;
        }

        $date = DateTime::createFromFormat(XsTime::FORMAT, $value[0]->nodeValue);
        if ($date === false) {
            throw new \UnexpectedValueException('Cannot initialize DateTime from ' . $value[0]->nodeValue);
        }

        return self::$timeFormatter->format($date, $picture, $locale, 'AD');
    }

    /**
     * @param DOMElement[] $value
     * @param string $picture
     * @param string|null $language
     * @return string
     * @throws InvalidArgumentException
     */
    public static function formatDateTime($value, string $picture, string $language = null)
    {
        Assert::assertArray($value);
        Assert::assertSchema($value[0], 'dateTime');

        if (self::$dateTimeFormatter === null) {
            // @codeCoverageIgnoreStart
            if (\extension_loaded('intl')) {
                self::$dateTimeFormatter = Functions\Formatter\IntlDateTimeFormatter::createWithFlagDateTime();
            } else {
                self::$dateTimeFormatter = Functions\Formatter\DateTimeFormatter::createWithFlagDateTime();
            }
            // @codeCoverageIgnoreEnd
        }

        if ($language === null) {
            $locale = self::detectSystemLocale();
        } else {
            $locale = $language;
        }

        $date = DateTime::createFromFormat(XsDateTime::FORMAT, $value[0]->nodeValue);
        if ($date === false) {
            throw new \UnexpectedValueException('Cannot initialize DateTime from ' . $value[0]->nodeValue);
        }

        return self::$dateTimeFormatter->format($date, $picture, $locale, 'AD');
    }

    /**
     * @return string
     */
    private static function detectSystemLocale()
    {
        if (self::$locale === null) {
            self::$locale = self::getSystemLocale();
        }

        return self::$locale;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    private static function getSystemLocale(): string
    {
        if (\class_exists(Locale::class)) {
            return Locale::getDefault();
        }

        $locale = \setlocale(LC_ALL, null);
        if ($locale === false) {
            throw new \UnexpectedValueException('Cannot determine locale of this system');
        }

        return $locale;
    }
}
