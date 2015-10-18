<?php
namespace Genkgo\Xsl\Schema;

use DateTimeImmutable;
use DOMDocument;

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
     * @return string
     */
    protected function getElementName()
    {
        return 'time';
    }

    /**
     * @param DateTimeImmutable $date
     * @return static
     */
    public static function fromDateTime(DateTimeImmutable $date)
    {
        return new static($date->format(self::FORMAT));
    }
}
