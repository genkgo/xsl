<?php
namespace Genkgo\Xsl\Xsl\Functions\DateComponent;

use DateTimeInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\ComponentInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

/**
 * Class MinutesComponent
 * @package Genkgo\Xsl\Xsl\Functions\DateComponent
 */
final class TimezoneOffsetGmtComponent implements ComponentInterface {

    /**
     * @param PictureString $pictureString
     * @param DateTimeInterface $date
     * @return string
     */
    public function format(PictureString $pictureString, DateTimeInterface $date)
    {
        return '\G\M\TP';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'z';
    }
}