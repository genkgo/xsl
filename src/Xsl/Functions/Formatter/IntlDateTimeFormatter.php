<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions\Formatter;

use DateTimeInterface;
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

final class IntlDateTimeFormatter implements FormatterInterface
{
    public const ESCAPE = '\'';

    /**
     * @var string
     */
    private $defaultTimezone;

    /**
     * @param string $defaultTimezone
     */
    public function __construct(string $defaultTimezone)
    {
        $this->defaultTimezone = $defaultTimezone;
    }

    /**
     * @param DateTimeInterface $date
     * @param string $picture
     * @param string $locale
     * @param string $calendar
     * @return string
     */
    public function formatDate(DateTimeInterface $date, string $picture, string $locale, string $calendar = ''): string
    {
        return $this->format(
            $date,
            $this->newComponentsMap([
                new TimezoneOffsetUtcComponent(),
                new TimezoneOffsetGmtComponent(),
                new YearComponent(),
                new MonthComponent(),
                new DayInMonthComponent(),
                new DayInYearComponent(),
                new DayOfWeekComponent(),
                new WeekOfYearComponent(),
            ]),
            $picture,
            $locale,
            $calendar
        );
    }

    /**
     * @param DateTimeInterface $date
     * @param string $picture
     * @param string $locale
     * @param string $calendar
     * @return string
     */
    public function formatTime(DateTimeInterface $date, string $picture, string $locale, string $calendar = ''): string
    {
        return $this->format(
            $date,
            $this->newComponentsMap([
                new TimezoneOffsetUtcComponent(),
                new TimezoneOffsetGmtComponent(),
                new HourInDayComponent(),
                new HourInHalfDayComponent(),
                new MinutesComponent(),
                new SecondsComponent(),
                new AmPmMarkerComponent(),
            ]),
            $picture,
            $locale,
            $calendar
        );
    }

    /**
     * @param DateTimeInterface $date
     * @param string $picture
     * @param string $locale
     * @param string $calendar
     * @return string
     */
    public function formatDateTime(DateTimeInterface $date, string $picture, string $locale, string $calendar = ''): string
    {
        return $this->format(
            $date,
            $this->newComponentsMap([
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
            ]),
            $picture,
            $locale,
            $calendar
        );
    }

    /**
     * @param DateTimeInterface $date
     * @param array $components
     * @param string $picture
     * @param string $locale
     * @param string $calendar
     * @return string
     * @credits https://github.com/Saxonica/Saxon-CE/ https://github.com/Saxonica/Saxon-CE/blob/master/notices/MOZILLA.txt
     */
    private function format(DateTimeInterface $date, array $components, string $picture, string $locale, string $calendar = ''): string
    {
        $result = [];

        $i = 0;
        $escaped = false;
        while (true) {
            while ($i < \strlen($picture) && \substr($picture, $i, 1) !== '[') {
                if ($escaped === false) {
                    $result[] = self::ESCAPE;
                    $escaped = true;
                }

                $result[] = \substr($picture, $i, 1);

                if (\substr($picture, $i, 1) === ']') {
                    $i++;
                    if ($i == \strlen($picture) || \substr($picture, $i, 1) != ']') {
                        throw new \InvalidArgumentException('Wrong formatted date, escape by doubling [[ and ]]', 1340);
                    }
                }
                $i++;
            }

            if ($i === \strlen($picture)) {
                break;
            }

            // look for '[['
            $i++;

            if ($i < \strlen($picture) && \substr($picture, $i, 1) === '[') {
                $result[] = '[';
                $i++;
            } else {
                $close = ($i < \strlen($picture) ? \strpos($picture, "]", $i) : -1);
                if ($close === -1 || $close === false) {
                    throw new \InvalidArgumentException('Wrong formatted date, missing ]', 1340);
                }

                $pictureString = new PictureString(\substr($picture, $i, $close - $i));
                $specifier = $pictureString->getComponentSpecifier();

                if (isset($components[$specifier])) {
                    if ($pictureString->getPresentationModifier() === 'N') {
                        if ($escaped === false) {
                            $result[] = self::ESCAPE;
                            $escaped = true;
                        }

                        $result[] = \strtoupper(
                            $this->formatPattern(
                                $date,
                                $locale,
                                $components[$specifier]->format($pictureString, $date)
                            )
                        );
                    } elseif ($pictureString->getPresentationModifier() === 'n') {
                        if ($escaped === false) {
                            $result[] = self::ESCAPE;
                            $escaped = true;
                        }

                        $result[] = \strtolower(
                            $this->formatPattern(
                                $date,
                                $locale,
                                $components[$specifier]->format($pictureString, $date)
                            )
                        );
                    } else {
                        if ($escaped) {
                            $result[] = self::ESCAPE;
                            $escaped = false;
                        }

                        $result[] = $components[$specifier]->format($pictureString, $date);
                    }
                } else {
                    throw new \InvalidArgumentException("Component [{$specifier}] is not supported", 1340);
                }

                $i = $close + 1;
            }
        }

        if ($escaped) {
            $result[] = self::ESCAPE;
        }

        return $this->formatPattern($date, $locale, \implode('', $result));
    }

    /**
     * @param DateTimeInterface $date
     * @param string $locale
     * @param string $pattern
     * @return string
     */
    private function formatPattern(DateTimeInterface $date, string $locale, string $pattern): string
    {
        return (
            new IntlDateFormatter(
                $locale,
                IntlDateFormatter::FULL,
                IntlDateFormatter::FULL,
                $this->defaultTimezone,
                IntlDateFormatter::GREGORIAN,
                $pattern
            )
        )->format($date);
    }

    /**
     * @param array|ComponentInterface[] $components
     * @return array
     */
    private function newComponentsMap(array $components):array
    {
        $map = [];

        foreach ($components as $component) {
            $map[(string)$component] = $component;
        }

        return $map;
    }
}
