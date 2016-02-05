<?php
namespace Genkgo\Xsl\Xsl\Functions\Formatter;

use DateTimeImmutable;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;
use Genkgo\Xsl\Xsl\Functions\DateComponent\AmPmMarkerComponent;
use Genkgo\Xsl\Xsl\Functions\DateComponent\ComponentInterface;
use Genkgo\Xsl\Xsl\Functions\DateComponent\DayInMonthComponent;
use Genkgo\Xsl\Xsl\Functions\DateComponent\DayInYearComponent;
use Genkgo\Xsl\Xsl\Functions\DateComponent\DayOfWeekComponent;
use Genkgo\Xsl\Xsl\Functions\DateComponent\HourInDayComponent;
use Genkgo\Xsl\Xsl\Functions\DateComponent\HourInHalfDayComponent;
use Genkgo\Xsl\Xsl\Functions\DateComponent\MinutesComponent;
use Genkgo\Xsl\Xsl\Functions\DateComponent\MonthComponent;
use Genkgo\Xsl\Xsl\Functions\DateComponent\SecondsComponent;
use Genkgo\Xsl\Xsl\Functions\DateComponent\TimezoneOffsetGmtComponent;
use Genkgo\Xsl\Xsl\Functions\DateComponent\TimezoneOffsetUtcComponent;
use Genkgo\Xsl\Xsl\Functions\DateComponent\WeekOfYearComponent;
use Genkgo\Xsl\Xsl\Functions\DateComponent\YearComponent;

/**
 * Class DateTimeFormatting
 * @package Genkgo\Xsl\Xsl\Functions\Formatter
 */
class DateTimeFormatter {

    /**
     * @var array|ComponentInterface[]
     */
    private $components = [];

    /**
     * @param array $components
     */
    private function mapComponents(array $components) {
        foreach ($components as $component) {
            $this->components[(string)$component] = $component;
        }
    }

    /**
     * @param DateTimeImmutable $date
     * @param string $picture
     * @param $language
     * @return string
     * @throws InvalidArgumentException
     * @credits https://github.com/Saxonica/Saxon-CE/ https://github.com/Saxonica/Saxon-CE/blob/master/notices/MOZILLA.txt
     */
    public function format(DateTimeImmutable $date, $picture, $language, $calendar, $country)
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

                $pictureString = new PictureString(substr($picture, $i, $close));
                $specifier = $pictureString->getComponentSpecifier();

                if (isset($this->components[$specifier])) {
                    $result[] = $this->components[$specifier]->format(
                        $date,
                        $pictureString,
                        $language,
                        $calendar,
                        $country
                    );
                } else {
                    $exception = new InvalidArgumentException("Component [{$specifier}] is not supported");
                    $exception->setErrorCode('XTDE1340');
                    throw $exception;
                }

                $i = $close + 1;
            }
        }

        return implode('', $result);
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