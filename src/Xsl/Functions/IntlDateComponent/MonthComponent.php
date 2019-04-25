<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions\IntlDateComponent;

use DateTimeInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\ComponentInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

final class MonthComponent implements ComponentInterface
{
    /**
     * @param PictureString $pictureString
     * @param DateTimeInterface $date
     * @return string
     */
    public function format(PictureString $pictureString, DateTimeInterface $date): string
    {
        $presentation = $pictureString->getPresentationModifier();
        $maxWidth = $pictureString->getMaxWidth();

        if ($presentation === 'Nn' || $presentation === 'N' || $presentation === 'n') {
            if ($maxWidth !== null && $maxWidth <= 3) {
                return 'MMM';
            } else {
                return 'MMMM';
            }
        }

        if ($maxWidth === null || $maxWidth > 1) {
            return 'MM';
        } else {
            return 'M';
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'M';
    }
}
