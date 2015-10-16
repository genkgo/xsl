<?php
namespace Genkgo\Xsl\Xsl\Functions;

use DateTimeImmutable;
use Genkgo\Xsl\Schema\XsDate;
use Genkgo\Xsl\Schema\XsDateTime;
use Genkgo\Xsl\Schema\XsTime;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;
use Genkgo\Xsl\Xsl\Functions;

trait Formatting
{

    /**
     * @param $value
     * @param $picture
     * @return string
     * @throws InvalidArgumentException
     */
    public static function formatDate ($value, $picture) {
        if (count($value) === 0) {
            return '';
        }

        if ($value[0]->documentElement->nodeName !== 'xs:date') {
            throw new InvalidArgumentException('format-date expects a date object');
        }

        $date = DateTimeImmutable::createFromFormat(XsDate::FORMAT, $value[0]->documentElement->nodeValue);
        return self::formatEvaluatedDateTime($date, $picture);
    }

    /**
     * @param $value
     * @param $picture
     * @return string
     * @throws InvalidArgumentException
     */
    public static function formatTime ($value, $picture) {
        if (count($value) === 0) {
            return '';
        }

        if ($value[0]->documentElement->nodeName !== 'xs:time') {
            throw new InvalidArgumentException('format-date expects a date object');
        }

        $date = DateTimeImmutable::createFromFormat(XsTime::FORMAT, $value[0]->documentElement->nodeValue);
        return self::formatEvaluatedDateTime($date, $picture);
    }

    /**
     * @param $value
     * @param $picture
     * @return string
     * @throws InvalidArgumentException
     */
    public static function formatDateTime ($value, $picture) {
        if (count($value) === 0) {
            return '';
        }

        if ($value[0]->documentElement->nodeName !== 'xs:dateTime') {
            throw new InvalidArgumentException('format-date expects a date object');
        }

        $date = DateTimeImmutable::createFromFormat(XsDateTime::FORMAT, $value[0]->documentElement->nodeValue);
        return self::formatEvaluatedDateTime($date, $picture);
    }

    /**
     * @param DateTimeImmutable $date
     * @param $picture
     * @return string
     * @throws InvalidArgumentException
     * @credits https://github.com/Saxonica/Saxon-CE/ https://github.com/Saxonica/Saxon-CE/blob/master/notices/MOZILLA.txt
     */
    private static function formatEvaluatedDateTime (DateTimeImmutable $date, $picture) {
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
                if ($close == -1) {
                    // throw here
                }
                $componentFormat = substr($picture, $i, $close);
                $result[] = self::formatDateComponent($date, $componentFormat, Functions::FLAG_DATE);
                $i = $close + 1;
            }
        }

        return implode('', $result);
    }

    private static function formatDateComponent (DateTimeImmutable $date, $componentFormat, $flags) {
        $matches = preg_match_all('/([YMDdWwFHhmsfZzPCE])\\s*(.*)/', $componentFormat, $groups);
        if ($matches === 0) {
            // throw here
        }

        $ignoreDate = $flags & Functions::FLAG_DATE === 0;
        $ignoreTime = $flags & Functions::FLAG_TIME === 0;

        $component = $groups[1][0];
        if ($component == null) {
            $component = "";
        }

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
            case 'w':   // week in month
                throw new InvalidArgumentException('Week in month is not supported');
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
            case 'f':   // fractional seconds
                throw new InvalidArgumentException('Fractional seconds are not supported');
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
            case 'C':   // calendar
                throw new InvalidArgumentException('Calendar name is not supported');
            case 'E':   // era
                throw new InvalidArgumentException('Era is not supported');
            default:
                $exception = new InvalidArgumentException('Unknown component' . $component);
                $exception->setErrorCode('XTDE1340');
                throw $exception;
        }
    }

}