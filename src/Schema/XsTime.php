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

    protected function getElementName()
    {
        return 'time';
    }

    public static function fromDateTime (DateTimeImmutable $date)
    {
        return new static($date->format(self::FORMAT));
    }
}
