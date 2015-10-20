<?php
namespace Genkgo\Xsl\Schema;

use DateTimeImmutable;
use DOMDocument;
use Genkgo\Xsl\Exception\CastException;

/**
 * Class XsTime
 * @package Genkgo\Xsl\Schema
 */
final class XsTime extends AbstractXsElement
{
    /**
     *
     */
    const FORMAT = 'H:i:sP';

    /**
     * @var array
     */
    private static $formats = [self::FORMAT, 'H:i:s'];

    /**
     * @return string
     */
    protected function getElementName()
    {
        return 'time';
    }

    /**
     * @param string $date
     * @return XsTime
     * @throws CastException
     */
    public static function fromString($date)
    {
        foreach (static::$formats as $format) {
            $value = DateTimeImmutable::createFromFormat($format, $date);
            if ($value) {
                return new static($value->format(self::FORMAT));
            }
        }

        throw new CastException('Cannot create time from ' . $date);
    }

    /**
     * @return XsTime
     */
    public static function now()
    {
        return new static((new DateTimeImmutable())->format(self::FORMAT));
    }
}
