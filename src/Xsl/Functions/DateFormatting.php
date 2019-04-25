<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions;

use DateTime;
use Genkgo\Xsl\Schema\XsDate;
use Genkgo\Xsl\Schema\XsDateTime;
use Genkgo\Xsl\Schema\XsTime;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Util\Assert;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;
use Genkgo\Xsl\Xsl\Functions;
use Locale;

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
     * @param array $arguments
     * @param TransformationContext $context
     * @return string
     * @throws InvalidArgumentException
     */
    public function formatDate(array $arguments, TransformationContext $context)
    {
        Assert::assertArray($arguments);
        Assert::assertArray($arguments[0]);

        if (\count($arguments[0]) === 0) {
            return '';
        }

        Assert::assertSchema($arguments[0][0], 'date');

        $locale = $arguments[2] ?? Locale::getDefault();

        $date = DateTime::createFromFormat(XsDate::FORMAT, $arguments[0][0]->nodeValue);
        if ($date === false) {
            throw new \UnexpectedValueException('Cannot initialize DateTime from ' . $arguments[0][0]->nodeValue);
        }

        return $this->formatter->formatDate($date, $arguments[1], $locale, 'AD');
    }

    /**
     * @param array $arguments
     * @param TransformationContext $context
     * @return string
     * @throws InvalidArgumentException
     */
    public function formatTime(array $arguments, TransformationContext $context)
    {
        Assert::assertArray($arguments);
        Assert::assertArray($arguments[0]);

        if (\count($arguments[0]) === 0) {
            return '';
        }

        Assert::assertSchema($arguments[0][0], 'time');

        $locale = $arguments[2] ?? Locale::getDefault();

        $date = DateTime::createFromFormat(XsTime::FORMAT, $arguments[0][0]->nodeValue);
        if ($date === false) {
            throw new \UnexpectedValueException('Cannot initialize DateTime from ' . $arguments[0][0]->nodeValue);
        }

        return $this->formatter->formatTime($date, $arguments[1], $locale, 'AD');
    }

    /**
     * @param array $arguments
     * @param TransformationContext $context
     * @return string
     * @throws InvalidArgumentException
     */
    public function formatDateTime(array $arguments, TransformationContext $context)
    {
        Assert::assertArray($arguments);
        Assert::assertArray($arguments[0]);

        if (\count($arguments[0]) === 0) {
            return '';
        }

        Assert::assertSchema($arguments[0][0], 'dateTime');

        $locale = $arguments[2] ?? Locale::getDefault();

        $date = DateTime::createFromFormat(XsDateTime::FORMAT, $arguments[0][0]->nodeValue);
        if ($date === false) {
            throw new \UnexpectedValueException('Cannot initialize DateTime from ' . $arguments[0][0]->nodeValue);
        }

        return $this->formatter->formatDateTime($date, $arguments[1], $locale, 'AD');
    }
}
