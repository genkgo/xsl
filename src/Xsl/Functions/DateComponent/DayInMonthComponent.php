<?php
namespace Genkgo\Xsl\Xsl\Functions\DateComponent;

use DateTimeImmutable;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

/**
 * Class DayInMonthComponent
 * @package Genkgo\Xsl\Xsl\Functions\DateComponent
 */
final class DayInMonthComponent implements ComponentInterface {

    /**
     * @param DateTimeImmutable $date
     * @param PictureString $pictureString
     * @param $language
     * @param $calendar
     * @param $country
     * @return string
     */
    public function format(DateTimeImmutable $date, PictureString $pictureString, $language, $calendar, $country)
    {
        return $date->format('d');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'D';
    }
}