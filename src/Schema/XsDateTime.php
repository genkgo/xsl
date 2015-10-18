<?php
namespace Genkgo\Xsl\Schema;

use DateTimeImmutable;
use DOMDocument;

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
     * @return string
     */
    protected function getElementName()
    {
        return 'dateTime';
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
