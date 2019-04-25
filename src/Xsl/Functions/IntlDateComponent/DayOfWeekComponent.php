<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions\IntlDateComponent;

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
    public function format(PictureString $pictureString, DateTimeInterface $date): string
    {
        $presentation = $pictureString->getPresentationModifier();
        if ($presentation === 'Nn' || $presentation === 'N' || $presentation === 'n') {
            $maxWidth = $pictureString->getMaxWidth();
            if ($maxWidth !== null && $maxWidth <= 3) {
                return 'EEE';
            } else {
                return 'EEEE';
            }
        }

        return 'EEEE';
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'F';
    }
}
