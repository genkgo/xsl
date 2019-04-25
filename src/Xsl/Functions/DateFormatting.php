<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions;

use Genkgo\Xsl\Callback\Arguments;
use Genkgo\Xsl\Schema\XsDate;
use Genkgo\Xsl\Schema\XsDateTime;
use Genkgo\Xsl\Schema\XsTime;
use Genkgo\Xsl\Xsl\Functions;

final class DateFormatting
{
    /**
     * @var Formatter\FormatterInterface
     */
    private $formatter;

    /**
     * @param Formatter\FormatterInterface $formatter
     */
    public function __construct(Functions\Formatter\FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @param Arguments $arguments
     * @return string
     */
    public function formatDate(Arguments $arguments)
    {
        if ($arguments->get(0) === []) {
            return '';
        }

        $arguments->assertSchema(0, 'date');

        try {
            $locale = $arguments->castAsScalar(2);
        } catch (\InvalidArgumentException $e) {
            $locale = \Locale::getDefault();
        }

        $date = \DateTime::createFromFormat(XsDate::FORMAT, $arguments->castAsScalar(0));
        if ($date === false) {
            throw new \UnexpectedValueException('Cannot initialize DateTime from ' . $arguments->castAsScalar(0));
        }

        return $this->formatter->formatDate($date, $arguments->castAsScalar(1), $locale, 'AD');
    }

    /**
     * @param Arguments $arguments
     * @return string
     */
    public function formatTime(Arguments $arguments)
    {
        if ($arguments->get(0) === []) {
            return '';
        }

        $arguments->assertSchema(0, 'time');

        try {
            $locale = $arguments->castAsScalar(2);
        } catch (\InvalidArgumentException $e) {
            $locale = \Locale::getDefault();
        }

        $date = \DateTime::createFromFormat(XsTime::FORMAT, $arguments->castAsScalar(0));
        if ($date === false) {
            throw new \UnexpectedValueException('Cannot initialize DateTime from ' . $arguments->castAsScalar(0));
        }

        return $this->formatter->formatTime($date, $arguments->castAsScalar(1), $locale, 'AD');
    }

    /**
     * @param Arguments $arguments
     * @return string
     */
    public function formatDateTime(Arguments $arguments)
    {
        if ($arguments->get(0) === []) {
            return '';
        }

        $arguments->assertSchema(0, 'dateTime');

        try {
            $locale = $arguments->castAsScalar(2);
        } catch (\InvalidArgumentException $e) {
            $locale = \Locale::getDefault();
        }

        $date = \DateTime::createFromFormat(XsDateTime::FORMAT, $arguments->castAsScalar(0));
        if ($date === false) {
            throw new \UnexpectedValueException('Cannot initialize DateTime from ' . $arguments->castAsScalar(0));
        }

        return $this->formatter->formatDateTime($date, $arguments->castAsScalar(1), $locale, 'AD');
    }
}
