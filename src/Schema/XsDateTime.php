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

    protected function getElementName()
    {
        return 'dateTime';
    }

    public static function fromDateTime (DateTimeImmutable $date)
    {
        return new static($date->format(self::FORMAT));
    }
}
