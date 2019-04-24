<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Schema;

use DateInterval;
use Genkgo\Xsl\Exception\CastException;

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
        try {
            new DateInterval($date);
            return new self($date);
        } catch (\Exception $e) {
            throw new CastException('Cannot create dayTimeDuration from ' . $date);
        }
    }
}
