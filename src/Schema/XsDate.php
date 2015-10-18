<?php
namespace Genkgo\Xsl\Schema;

use DateTimeImmutable;
use DOMDocument;

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
     * @return string
     */
    protected function getElementName()
    {
        return 'date';
    }

    /**
     * @param DateTimeImmutable $date
     * @return XsDate
     */
    public static function fromDateTime(DateTimeImmutable $date)
    {
        return new static($date->format(self::FORMAT));
    }
}
