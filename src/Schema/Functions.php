<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Schema;

use Genkgo\Xsl\Callback\Arguments;
use Genkgo\Xsl\Exception\CastException;

final class Functions
{
    /**
     * @param Arguments $arguments
     * @return XsDate
     * @throws CastException
     */
    public static function date(Arguments $arguments)
    {
        return XsDate::fromString($arguments->castFromSchemaType(0));
    }

    /**
     * @param Arguments $arguments
     * @return XsTime
     * @throws CastException
     */
    public static function time(Arguments $arguments)
    {
        return XsTime::fromString($arguments->castFromSchemaType(0));
    }

    /**
     * @param Arguments $arguments
     * @return XsDateTime
     * @throws CastException
     */
    public static function dateTime(Arguments $arguments)
    {
        return XsDateTime::fromString($arguments->castFromSchemaType(0));
    }

    /**
     * @param Arguments $arguments
     * @return XsDayTimeDuration
     * @throws CastException
     */
    public static function dayTimeDuration(Arguments $arguments)
    {
        return XsDayTimeDuration::fromString($arguments->castFromSchemaType(0));
    }

    /**
     * @param Arguments $arguments
     * @return XsInteger|XsSequence
     * @throws CastException
     */
    public static function integer(Arguments $arguments)
    {
        $value = $arguments->get(0);

        if (\is_bool($value)) {
            return new XsInteger((int)$value);
        }

        if (\is_array($value) && empty($value)) {
            return XsSequence::fromArray([]);
        }

        $value = $arguments->castFromSchemaType(0);

        if ($value === '') {
            return new XsInteger(0);
        }

        if (!\is_numeric($value) && !\is_bool($value)) {
            throw new CastException('Cannot cast to integer');
        }

        return new XsInteger((int)$value);
    }
}
