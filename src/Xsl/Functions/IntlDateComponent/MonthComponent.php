<?php
namespace Genkgo\Xsl\Xsl\Functions\IntlDateComponent;

use DateTimeInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\ComponentInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

/**
 * Class MonthComponent
 * @package Genkgo\Xsl\Xsl\Functions\DateComponent
 */
final class MonthComponent implements ComponentInterface {

    /**
     * @param PictureString $pictureString
     * @param DateTimeInterface $date
     * @return string
     */
    public function format(PictureString $pictureString, DateTimeInterface $date)
    {
        $presentation = $pictureString->getPresentationModifier();
        if ($presentation === 'Nn' || $presentation === 'N' || $presentation === 'n') {
            return 'MMMM';
        }

        return 'MM';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'M';
    }
}