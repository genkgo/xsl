<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions;

use Genkgo\Xsl\Callback\Arguments;
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

        $arguments->assertSchemaType(0, 'date');

        try {
            $locale = $arguments->castFromSchemaType(2);
        } catch (\InvalidArgumentException $e) {
            $locale = \Locale::getDefault();
        }

        return $this->formatter->formatDate($arguments->castFromSchemaType(0), $arguments->castFromSchemaType(1), $locale, 'AD');
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

        $arguments->assertSchemaType(0, 'time');

        try {
            $locale = $arguments->castFromSchemaType(2);
        } catch (\InvalidArgumentException $e) {
            $locale = \Locale::getDefault();
        }

        return $this->formatter->formatTime(
            $arguments->castFromSchemaType(0),
            $arguments->castFromSchemaType(1),
            $locale,
            'AD'
        );
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

        $arguments->assertSchemaType(0, 'dateTime');

        try {
            $locale = $arguments->castFromSchemaType(2);
        } catch (\InvalidArgumentException $e) {
            $locale = \Locale::getDefault();
        }

        return $this->formatter->formatDateTime(
            $arguments->castFromSchemaType(0),
            $arguments->castFromSchemaType(1),
            $locale,
            'AD'
        );
    }
}
