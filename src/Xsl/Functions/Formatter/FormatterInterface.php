<?php
namespace Genkgo\Xsl\Xsl\Functions\Formatter;

use DateTimeInterface;

/**
 * Interface FormatterInterface
 * @package Genkgo\Xsl\Xsl\Functions\Formatter
 */
interface FormatterInterface {

    /**
     * @param DateTimeInterface $date
     * @param $picture
     * @param $locale
     * @param $calendar
     * @return mixed
     */
    public function format(DateTimeInterface $date, $picture, $locale, $calendar);

}