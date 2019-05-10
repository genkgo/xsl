<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Schema;

use Genkgo\Xsl\Exception\CastException;

final class XsDateTime extends AbstractXsElement
{
    const FORMAT = DATE_W3C;

    /**
     * @var array
     */
    private static $formats = [self::FORMAT, 'Y-m-d H:i:sP', 'Y-m-d H:i:s', 'Y-m-d P|', 'Y-m-d|'];

    /**
     * @return string
     */
    protected function getElementName(): string
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
            $value = \DateTimeImmutable::createFromFormat($format, $date);
            if ($value) {
                return new self($value->format(self::FORMAT));
            }
        }

        throw new CastException('Cannot create dateTime from ' . $date);
    }

    /**
     * @param \DOMNode $node
     * @return \DateTimeImmutable
     */
    public static function parseNode(\DOMNode $node): \DateTimeImmutable
    {
        $result = \DateTimeImmutable::createFromFormat(self::FORMAT, $node->textContent);

        if ($result === false) {
            throw new \InvalidArgumentException('Cannot parse dateTime from ' . $node->textContent);
        }

        return $result;
    }

    /**
     * @return XsDateTime
     */
    public static function now()
    {
        return new self((new \DateTimeImmutable())->format(self::FORMAT));
    }
}
