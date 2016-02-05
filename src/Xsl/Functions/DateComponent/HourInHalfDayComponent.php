<?php
namespace Genkgo\Xsl\Xsl\Functions\DateComponent;

use DateTimeImmutable;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

/**
 * Class HourInHalfDayComponent
 * @package Genkgo\Xsl\Xsl\Functions\DateComponent
 */
final class HourInHalfDayComponent implements ComponentInterface {

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
        return $date->format('h');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'h';
    }
}