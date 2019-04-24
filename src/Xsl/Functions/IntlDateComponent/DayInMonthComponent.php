<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions\IntlDateComponent;

use DateTimeInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\ComponentInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

final class DayInMonthComponent implements ComponentInterface
{
    /**
     * @param PictureString $pictureString
     * @return string
     */
    public function format(PictureString $pictureString, DateTimeInterface $date)
    {
        $maxWidth = $pictureString->getMaxWidth();
        if ($maxWidth === null || $maxWidth > 1) {
            return 'dd';
        } else {
            return 'd';
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'D';
    }
}
