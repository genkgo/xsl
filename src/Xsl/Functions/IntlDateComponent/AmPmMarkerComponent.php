<?php
namespace Genkgo\Xsl\Xsl\Functions\IntlDateComponent;

use DateTimeInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\ComponentInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

/**
 * Class AmPmMarkerComponent
 * @package Genkgo\Xsl\Xsl\Functions\DateComponent
 */
final class AmPmMarkerComponent implements ComponentInterface {

    /**
     * @param PictureString $pictureString
     * @return string
     */
    public function format(PictureString $pictureString, DateTimeInterface $date)
    {
        return 'a';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'P';
    }
}