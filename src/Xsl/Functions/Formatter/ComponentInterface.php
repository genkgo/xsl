<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions\Formatter;

use DateTimeInterface;

interface ComponentInterface
{
    /**
     * @param PictureString $pictureString
     * @param DateTimeInterface $date
     * @return string
     */
    public function format(PictureString $pictureString, DateTimeInterface $date): string;

    /**
     * @return string
     */
    public function __toString(): string;
}
