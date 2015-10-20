<?php
namespace Genkgo\Xsl\Schema;

use DateTimeImmutable;
use DOMDocument;
use Genkgo\Xsl\Exception\CastException;

/**
 * Class XsDateTime
 * @package Genkgo\Xsl\Schema
 */
final class XsDateTime extends AbstractXsElement
{
    /**
     *
     */
    const FORMAT = 'Y-m-d H:i:sP';

    /**
     * @var array
     */
    private static $formats = [self::FORMAT, 'Y-m-d H:i:s', 'Y-m-d P|', 'Y-m-d|'];

    /**
     * @return string
     */
    protected function getElementName()
    {
        return 'dateTime';
    }

    /**
     * @param string $date
     * @return XsDateTime
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

        throw new CastException('Cannot create dateTime from ' . $date);
    }

    /**
     * @return XsDateTime
     */
    public static function now()
    {
        return new static((new DateTimeImmutable())->format(self::FORMAT));
    }
}
