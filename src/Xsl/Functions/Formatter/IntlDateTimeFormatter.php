<?php
namespace Genkgo\Xsl\Xsl\Functions\Formatter;

use DateTimeInterface;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;
use Genkgo\Xsl\Xsl\Functions\IntlDateComponent\AmPmMarkerComponent;
use Genkgo\Xsl\Xsl\Functions\IntlDateComponent\DayInMonthComponent;
use Genkgo\Xsl\Xsl\Functions\IntlDateComponent\DayInYearComponent;
use Genkgo\Xsl\Xsl\Functions\IntlDateComponent\DayOfWeekComponent;
use Genkgo\Xsl\Xsl\Functions\IntlDateComponent\HourInDayComponent;
use Genkgo\Xsl\Xsl\Functions\IntlDateComponent\HourInHalfDayComponent;
use Genkgo\Xsl\Xsl\Functions\IntlDateComponent\MinutesComponent;
use Genkgo\Xsl\Xsl\Functions\IntlDateComponent\MonthComponent;
use Genkgo\Xsl\Xsl\Functions\IntlDateComponent\SecondsComponent;
use Genkgo\Xsl\Xsl\Functions\IntlDateComponent\TimezoneOffsetGmtComponent;
use Genkgo\Xsl\Xsl\Functions\IntlDateComponent\TimezoneOffsetUtcComponent;
use Genkgo\Xsl\Xsl\Functions\IntlDateComponent\WeekOfYearComponent;
use Genkgo\Xsl\Xsl\Functions\IntlDateComponent\YearComponent;
use IntlDateFormatter;

/**
 * Class DateTimeFormatting
 * @package Genkgo\Xsl\Xsl\Functions\Formatter
 */
class IntlDateTimeFormatter implements FormatterInterface {

    const ESCAPE = '\'';

    /**
     * @var array|ComponentInterface[]
     */
    private $components = [];
    /**
     * @var string
     */
    private $timezone;

    /**
     * @param array $components
     */
    private function mapComponents(array $components) {
        foreach ($components as $component) {
            $this->components[(string)$component] = $component;
        }
        $this->timezone = date_default_timezone_get();
    }

    /**
     * @param DateTimeInterface $date
     * @param string $picture
     * @param $locale
     * @param $calendar
     * @return string
     * @throws InvalidArgumentException
     * @credits https://github.com/Saxonica/Saxon-CE/ https://github.com/Saxonica/Saxon-CE/blob/master/notices/MOZILLA.txt
     */
    public function format(DateTimeInterface $date, $picture, $locale, $calendar)
    {
        $result = [];

        $i = 0;
        $escaped = false;
        while (true) {

            while ($i < strlen($picture) && substr($picture, $i, 1) !== '[') {
                if ($escaped === false) {
                    $result[] = self::ESCAPE;
                    $escaped = true;
                }

                $result[] = substr($picture, $i, 1);

                if (substr($picture, $i, 1) === ']') {
                    $i++;
                    if ($i == strlen($picture) || substr($picture, $i, 1) != ']') {
                        $exception = new InvalidArgumentException('Wrong formatted date, escape by doubling [[ and ]]');
                        $exception->setErrorCode('XTDE1340');
                        throw $exception;
                    }
                }
                $i++;
            }

            if ($i === strlen($picture)) {
                break;
            }

            // look for '[['
            $i++;

            if ($i < strlen($picture) && substr($picture, $i, 1) === '[') {
                $result[] = '[';
                $i++;
            } else {
                $close = ($i < strlen($picture) ? strpos($picture, "]", $i) : -1);
                if ($close === -1 || $close === false) {
                    $exception = new InvalidArgumentException('Wrong formatted date, missing ]');
                    $exception->setErrorCode('XTDE1340');
                    throw $exception;
                }

                $pictureString = new PictureString(substr($picture, $i, $close - $i));
                $specifier = $pictureString->getComponentSpecifier();

                if (isset($this->components[$specifier])) {
                    if ($pictureString->getPresentationModifier() === 'N') {
                        if ($escaped === false) {
                            $result[] = self::ESCAPE;
                            $escaped = true;
                        }

                        $result[] = strtoupper(
                            $this->formatPattern(
                                $date,
                                $locale,
                                $this->components[$specifier]->format($pictureString, $date)
                            )
                        );

                    } elseif ($pictureString->getPresentationModifier() === 'n') {
                        if ($escaped === false) {
                            $result[] = self::ESCAPE;
                            $escaped = true;
                        }

                        $result[] = strtolower(
                            $this->formatPattern(
                                $date,
                                $locale,
                                $this->components[$specifier]->format($pictureString, $date)
                            )
                        );
                    } else {
                        if ($escaped) {
                            $result[] = self::ESCAPE;
                            $escaped = false;
                        }

                        $result[] = $this->components[$specifier]->format($pictureString, $date);
                    }
                } else {
                    $exception = new InvalidArgumentException("Component [{$specifier}] is not supported");
                    $exception->setErrorCode('XTDE1340');
                    throw $exception;
                }

                $i = $close + 1;
            }
        }

        if ($escaped) {
            $result[] = self::ESCAPE;
        }

        return $this->formatPattern($date, $locale, implode('', $result));
    }

    private function formatPattern (DateTimeInterface $date, $locale, $pattern) {
        return (
            new IntlDateFormatter(
                $locale,
                IntlDateFormatter::FULL,
                IntlDateFormatter::FULL,
                $this->timezone,
                IntlDateFormatter::GREGORIAN,
                $pattern
            )
        )->format($date);
    }

    /**
     * @return DateTimeFormatter
     */
    public static function createWithFlagDate()
    {
        $formatter = new self();
        $formatter->mapComponents([
            new TimezoneOffsetUtcComponent(),
            new TimezoneOffsetGmtComponent(),
            new YearComponent(),
            new MonthComponent(),
            new DayInMonthComponent(),
            new DayInYearComponent(),
            new DayOfWeekComponent(),
            new WeekOfYearComponent(),
        ]);
        return $formatter;
    }

    /**
     * @return DateTimeFormatter
     */
    public static function createWithFlagDateTime()
    {
        $formatter = new self();
        $formatter->mapComponents([
            new TimezoneOffsetUtcComponent(),
            new TimezoneOffsetGmtComponent(),
            new YearComponent(),
            new MonthComponent(),
            new DayInMonthComponent(),
            new DayInYearComponent(),
            new DayOfWeekComponent(),
            new WeekOfYearComponent(),
            new HourInDayComponent(),
            new HourInHalfDayComponent(),
            new MinutesComponent(),
            new SecondsComponent(),
            new AmPmMarkerComponent(),
        ]);
        return $formatter;
    }

    /**
     * @return DateTimeFormatter
     */
    public static function createWithFlagTime()
    {
        $formatter = new self();
        $formatter->mapComponents([
            new TimezoneOffsetUtcComponent(),
            new TimezoneOffsetGmtComponent(),
            new HourInDayComponent(),
            new HourInHalfDayComponent(),
            new MinutesComponent(),
            new SecondsComponent(),
            new AmPmMarkerComponent(),
        ]);
        return $formatter;
    }
}
