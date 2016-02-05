<?php
namespace Genkgo\Xsl\Xsl\Functions\Formatter;
use DateTimeInterface;

/**
 * Interface ComponentInterface
 * @package Genkgo\Xsl\Xsl\Functions\DateComponent
 */
interface ComponentInterface {

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