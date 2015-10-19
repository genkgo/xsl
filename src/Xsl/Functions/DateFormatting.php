<?php
namespace Genkgo\Xsl\Xsl\Functions;

use DateTimeImmutable;
use DOMElement;
use Genkgo\Xsl\Schema\XmlSchema;
use Genkgo\Xsl\Schema\XsDate;
use Genkgo\Xsl\Schema\XsDateTime;
use Genkgo\Xsl\Schema\XsTime;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;
use Genkgo\Xsl\Xsl\Functions;

/**
 * Class DateFormatter
 * @package Genkgo\Xsl\Xsl\Functions
 */
class DateFormatting
{
    /**
     *
     */
    const FLAG_DATE = 0x1;
    /**
     *
     */
    const FLAG_TIME = 0x2;

    /**
     * @param DOMElement[] $value
     * @param $picture
     * @return string
     * @throws InvalidArgumentException
     */
    public static function formatDate($value, $picture)
    {
        self::assertArray($value);
        self::assertSchema($value[0], 'date');

        $date = DateTimeImmutable::createFromFormat(XsDate::FORMAT, $value[0]->nodeValue);
        return self::formatEvaluatedDateTime($date, $picture, self::FLAG_DATE);
    }

    /**
     * @param DOMElement[] $value
     * @param string $picture
     * @return string
     * @throws InvalidArgumentException
     */
    public static function formatTime($value, $picture)
    {
        self::assertArray($value);
        self::assertSchema($value[0], 'time');

        $date = DateTimeImmutable::createFromFormat(XsTime::FORMAT, $value[0]->nodeValue);
        return self::formatEvaluatedDateTime($date, $picture, self::FLAG_TIME);
    }

    /**
     * @param DOMElement[] $value
     * @param string $picture
     * @return string
     * @throws InvalidArgumentException
     */
    public static function formatDateTime($value, $picture)
    {
        self::assertArray($value);
        self::assertSchema($value[0], 'dateTime');

        $date = DateTimeImmutable::createFromFormat(XsDateTime::FORMAT, $value[0]->nodeValue);
        return self::formatEvaluatedDateTime($date, $picture, self::FLAG_DATE + self::FLAG_TIME);
    }

    /**
     * @param $value
     * @throws InvalidArgumentException
     */
    private static function assertArray($value)
    {
        if (is_array($value) === false) {
            throw new InvalidArgumentException("Expected a date object, got scalar");
        }
    }

    /**
     * @param DOMElement $element
     * @param $name
     * @throws InvalidArgumentException
     */
    private static function assertSchema(DOMElement $element, $name)
    {
        if ($element->namespaceURI !== XmlSchema::URI || $element->localName !== $name) {
            $nsSchema = XmlSchema::URI;
            throw new InvalidArgumentException("Expected a {$nsSchema}:{$name} object, got {$element->nodeName}");
        }
    }

    /**
     * @param DateTimeImmutable $date
     * @param string $picture
     * @param int $flags
     * @return string
     * @throws InvalidArgumentException
     * @credits https://github.com/Saxonica/Saxon-CE/ https://github.com/Saxonica/Saxon-CE/blob/master/notices/MOZILLA.txt
     */
    private static function formatEvaluatedDateTime(DateTimeImmutable $date, $picture, $flags)
    {
        $result = [];

        $i = 0;
        while (true) {
            while ($i < strlen($picture) && substr($picture, $i, 1) != '[') {
                $result[] = substr($picture, $i, 1);
                if (substr($picture, $i, 1) == ']') {
                    $i++;
                    if ($i == strlen($picture) || substr($picture, $i, 1) != ']') {
                        $exception = new InvalidArgumentException('Wrong formatted date, escape by doubling [[ and ]]');
                        $exception->setErrorCode('XTDE1340');
                        throw $exception;
                    }
                }
                $i++;
            }

            if ($i == strlen($picture)) {
                break;
            }

            // look for '[['
            $i++;

            if ($i < strlen($picture) && substr($picture, $i, 1) == '[') {
                $result[] = '[';
                $i++;
            } else {
                $close = ($i < strlen($picture) ? strpos($picture, "]", $i) : -1);
                if ($close === -1 || $close === false) {
                    $exception = new InvalidArgumentException('Wrong formatted date, missing ]');
                    $exception->setErrorCode('XTDE1340');
                    throw $exception;
                }
                $componentFormat = substr($picture, $i, $close);
                $result[] = self::formatDateComponent($date, $componentFormat, $flags);
                $i = $close + 1;
            }
        }

        return implode('', $result);
    }

    /**
     * @param DateTimeImmutable $date
     * @param string $componentFormat
     * @param int $flags
     * @return string
     * @throws InvalidArgumentException
     *
     * @credits https://github.com/Saxonica/Saxon-CE/ https://github.com/Saxonica/Saxon-CE/blob/master/notices/MOZILLA.txt
     */
    private static function formatDateComponent(DateTimeImmutable $date, $componentFormat, $flags)
    {
        $matches = preg_match_all('/([YMDdWwFHhmsfZzPCE])\\s*(.*)/', $componentFormat, $groups);
        if ($matches === 0) {
            $exception = new InvalidArgumentException('No valid components found');
            $exception->setErrorCode('XTDE1340');
            throw $exception;
        }

        $ignoreDate = ($flags & self::FLAG_DATE) === 0;
        $ignoreTime = ($flags & self::FLAG_TIME) === 0;

        $component = $groups[1][0];

        switch (substr($component, 0, 1)) {
            case 'Y':   // year
                if ($ignoreDate) {
                    $exception = new InvalidArgumentException('Cannot use date context');
                    $exception->setErrorCode('XTDE1350');
                    throw $exception;
                }

                return $date->format('Y');
            case 'M':   // month
                if ($ignoreDate) {
                    $exception = new InvalidArgumentException('Cannot use date context');
                    $exception->setErrorCode('XTDE1350');
                    throw $exception;
                }

                return $date->format('m');
            case 'D':   // day in month
                if ($ignoreDate) {
                    $exception = new InvalidArgumentException('Cannot use date context');
                    $exception->setErrorCode('XTDE1350');
                    throw $exception;
                }

                return $date->format('d');
            case 'd':   // day in year
                if ($ignoreDate) {
                    $exception = new InvalidArgumentException('Cannot use date context');
                    $exception->setErrorCode('XTDE1350');
                    throw $exception;
                }

                return $date->format('z');
            case 'W':   // week of year
                if ($ignoreDate) {
                    $exception = new InvalidArgumentException('Cannot use date context');
                    $exception->setErrorCode('XTDE1350');
                    throw $exception;
                }

                return $date->format('W');
            case 'H':   // hour in day
                if ($ignoreTime) {
                    $exception = new InvalidArgumentException('Cannot use time context');
                    $exception->setErrorCode('XTDE1350');
                    throw $exception;
                }

                return $date->format('H');
            case 'h':   // hour in half-day (12 hour clock)
                if ($ignoreTime) {
                    $exception = new InvalidArgumentException('Cannot use time context');
                    $exception->setErrorCode('XTDE1350');
                    throw $exception;
                }

                return $date->format('h');
            case 'm':   // minutes
                if ($ignoreTime) {
                    $exception = new InvalidArgumentException('Cannot use time context');
                    $exception->setErrorCode('XTDE1350');
                    throw $exception;
                }

                return $date->format('i');
            case 's':   // seconds
                if ($ignoreTime) {
                    $exception = new InvalidArgumentException('Cannot use time context');
                    $exception->setErrorCode('XTDE1350');
                    throw $exception;
                }

                return $date->format('s');
            case 'Z':   // timezone in +hh:mm format, unless format=N in which case use timezone name
                return $date->format('P');
            case 'z':       // timezone
                return $date->format('O');
            case 'F':   // day of week
                if ($ignoreDate) {
                    $exception = new InvalidArgumentException('Cannot use date context');
                    $exception->setErrorCode('XTDE1350');
                    throw $exception;
                }

                return $date->format('O');
            case 'P':   // am/pm marker
                if ($ignoreTime) {
                    $exception = new InvalidArgumentException('Cannot use time context');
                    $exception->setErrorCode('XTDE1350');
                    throw $exception;
                }

                return $date->format('A');
        }

        $exception = new InvalidArgumentException("Component [{$component}] is not supported");
        $exception->setErrorCode('XTDE1340');
        throw $exception;
    }
}
