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
    public function format(DateTimeInterface $date, string $picture, string $locale, string $calendar = ''): string;
}
