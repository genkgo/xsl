<?php
namespace Genkgo\Xsl\Xsl\Functions\DateComponent;

use DateTimeImmutable;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

/**
 * Class SecondsComponent
 * @package Genkgo\Xsl\Xsl\Functions\DateComponent
 */
final class SecondsComponent implements ComponentInterface {

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
        return $date->format('s');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 's';
    }
}