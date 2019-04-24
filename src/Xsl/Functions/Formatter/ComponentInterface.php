<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions\Formatter;

use DateTimeInterface;

/**
 * Interface ComponentInterface
 */
interface ComponentInterface
{
    /**
     * @param PictureString $pictureString
     * @param DateTimeInterface $date
     * @return string
     */
    public function format(PictureString $pictureString, DateTimeInterface $date);

    /**
     * @return mixed
     */
    public function __toString();
}
