<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Schema;

use DateTimeImmutable;
use Genkgo\Xsl\Exception\CastException;

final class XsDate extends AbstractXsElement
{
    const FORMAT = 'Y-m-dP';

    /**
     * @var array
     */
    private static $formats = [self::FORMAT, 'Y-m-d'];

    /**
     * @return string
     */
    protected function getElementName(): string
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
                return new self($value->format(self::FORMAT));
            }
        }

        throw new CastException('Cannot create date from ' . $date);
    }

    /**
     * @param \DOMNode $node
     * @return DateTimeImmutable
     */
    public static function parseNode(\DOMNode $node): \DateTimeImmutable
    {
        $result = \DateTimeImmutable::createFromFormat(self::FORMAT, $node->textContent);

        if ($result === false) {
            throw new \InvalidArgumentException('Cannot parse date from ' . $node->textContent);
        }

        return $result;
    }

    /**
     * @return XsDate
     */
    public static function now()
    {
        return new self((new DateTimeImmutable())->format(self::FORMAT));
    }
}
