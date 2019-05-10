<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Schema;

use Genkgo\Xsl\Exception\CastException;

final class XsTime extends AbstractXsElement
{
    const FORMAT = 'H:i:sP';

    /**
     * @var array
     */
    private static $formats = [self::FORMAT, 'H:i:s'];

    /**
     * @return string
     */
    protected function getElementName(): string
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
            $value = \DateTimeImmutable::createFromFormat($format, $date);
            if ($value) {
                return new self($value->format(self::FORMAT));
            }
        }

        throw new CastException('Cannot create time from ' . $date);
    }

    /**
     * @param \DOMNode $node
     * @return \DateTimeImmutable
     */
    public static function parseNode(\DOMNode $node): \DateTimeImmutable
    {
        $result = \DateTimeImmutable::createFromFormat(self::FORMAT, $node->textContent);

        if ($result === false) {
            throw new \InvalidArgumentException('Cannot parse time from ' . $node->textContent);
        }

        return $result;
    }

    /**
     * @return XsTime
     */
    public static function now()
    {
        return new self((new \DateTimeImmutable())->format(self::FORMAT));
    }
}
