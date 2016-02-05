<?php
namespace Genkgo\Xsl\Xsl\Functions\DateComponent;

use DateTimeImmutable;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

/**
 * Class TimezoneOffsetUtcComponent
 * @package Genkgo\Xsl\Xsl\Functions\DateComponent
 */
final class TimezoneOffsetUtcComponent implements ComponentInterface {

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
        return $date->format('P');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Z';
    }
}