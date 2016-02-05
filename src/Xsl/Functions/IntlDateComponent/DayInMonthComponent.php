<?php
namespace Genkgo\Xsl\Xsl\Functions\IntlDateComponent;

use DateTimeInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\ComponentInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

/**
 * Class DayInMonthComponent
 * @package Genkgo\Xsl\Xsl\Functions\DateComponent
 */
final class DayInMonthComponent implements ComponentInterface {

    /**
     * @param PictureString $pictureString
     * @return string
     */
    public function format(PictureString $pictureString, DateTimeInterface $date)
    {
        return 'dd';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'D';
    }
}