<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions\DateComponent;

use DateTimeInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\ComponentInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

final class DayOfWeekComponent implements ComponentInterface
{
    /**
     * @param PictureString $pictureString
     * @param DateTimeInterface $date
     * @return string
     */
    public function format(PictureString $pictureString, DateTimeInterface $date)
    {
        return 'l';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'F';
    }
}
