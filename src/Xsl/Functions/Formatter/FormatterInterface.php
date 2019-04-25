<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions\Formatter;

use DateTimeInterface;

interface FormatterInterface
{
    /**
     * @param DateTimeInterface $date
     * @param string $picture
     * @param string $locale
     * @param string $calendar
     * @return string
     */
    public function formatDate(DateTimeInterface $date, string $picture, string $locale, string $calendar = ''): string;

    /**
     * @param DateTimeInterface $date
     * @param string $picture
     * @param string $locale
     * @param string $calendar
     * @return string
     */
    public function formatTime(DateTimeInterface $date, string $picture, string $locale, string $calendar = ''): string;

    /**
     * @param DateTimeInterface $date
     * @param string $picture
     * @param string $locale
     * @param string $calendar
     * @return string
     */
    public function formatDateTime(DateTimeInterface $date, string $picture, string $locale, string $calendar = ''): string;
}
