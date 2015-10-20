<?php
namespace Genkgo\Xsl\Schema;

use DateTimeImmutable;
use DOMDocument;
use Genkgo\Xsl\Exception\CastException;

/**
 * Class XsDate
 * @package Genkgo\Xsl\Schema
 */
final class XsDate extends AbstractXsElement
{
    /**
     *
     */
    const FORMAT = 'Y-m-dP';

    /**
     * @var array
     */
    private static $formats = [self::FORMAT, 'Y-m-d'];

    /**
     * @return string
     */
    protected function getElementName()
    {
        return 'date';
    }

    /**
     * @param string $date
     * @return XsDate
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

        throw new CastException('Cannot create date from ' . $date);
    }

    /**
     * @return XsDate
     */
    public static function now()
    {
        return new static((new DateTimeImmutable())->format(self::FORMAT));
    }
}
