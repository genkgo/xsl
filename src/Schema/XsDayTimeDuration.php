<?php
namespace Genkgo\Xsl\Schema;

use DateInterval;
use Genkgo\Xsl\Exception\CastException;

/**
 * Class XsDayTimeDuration
 * @package Genkgo\Xsl\Schema
 */
final class XsDayTimeDuration extends AbstractXsElement
{
    /**
     * @return string
     */
    protected function getElementName()
    {
        return 'dayTimeDuration';
    }

    /**
     * @param string $date
     * @return XsDayTimeDuration
     * @throws CastException
     */
    public static function fromString($date)
    {
        $value = new DateInterval($date);
        if ($value === false) {
            throw new CastException('Cannot create dayTimeDuration from ' . $date);
        }

        return new self($date);
    }
}
