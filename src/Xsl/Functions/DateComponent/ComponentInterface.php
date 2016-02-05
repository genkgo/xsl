<?php
namespace Genkgo\Xsl\Xsl\Functions\DateComponent;

use DateTimeImmutable;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

/**
 * Interface ComponentInterface
 * @package Genkgo\Xsl\Xsl\Functions\DateComponent
 */
interface ComponentInterface {

    /**
     * @param DateTimeImmutable $date
     * @param PictureString $pictureString
     * @param $language
     * @param $calendar
     * @param $country
     * @return mixed
     */
    public function format(DateTimeImmutable $date, PictureString $pictureString, $language, $calendar, $country);

    /**
     * @return mixed
     */
    public function __toString();

}