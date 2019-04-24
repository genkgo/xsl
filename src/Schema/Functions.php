<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Schema;

use Genkgo\Xsl\Exception\CastException;

final class Functions
{
    /**
     * @param mixed $value
     * @return XsDate
     */
    public static function xsDate($value)
    {
        $value = XsDate::castToNodeValue($value);
        return XsDate::fromString($value);
    }

    /**
     * @param mixed $value
     * @return XsTime
     */
    public static function xsTime($value)
    {
        $value = XsTime::castToNodeValue($value);
        return XsTime::fromString($value);
    }

    /**
     * @param mixed $value
     * @return XsDateTime
     */
    public static function xsDateTime($value)
    {
        $value = XsDateTime::castToNodeValue($value);
        return XsDateTime::fromString($value);
    }

    /**
     * @param mixed $value
     * @return XsDayTimeDuration
     */
    public static function xsDayTimeDuration($value)
    {
        $value = XsDayTimeDuration::castToNodeValue($value);
        return XsDayTimeDuration::fromString($value);
    }

    /**
     * @param mixed $value
     * @return XsInteger|XsSequence
     */
    public static function xsInteger($value)
    {
        if (\is_bool($value)) {
            return new XsInteger((int)$value);
        }

        if (\is_array($value) && empty($value)) {
            return XsSequence::fromArray([]);
        }

        $value = XsInteger::castToNodeValue($value);

        if ($value === '') {
            return new XsInteger(0);
        }

        if (!\is_numeric($value) && !\is_bool($value)) {
            throw new CastException('Cannot cast to integer');
        }

        return new XsInteger((int)$value);
    }
}
