<?php
namespace Genkgo\Xsl\Schema;

use DateTimeImmutable;
use DOMDocument;

/**
 * Class XsTime
 * @package Genkgo\Xsl\Schema
 */
final class XsTime extends DOMDocument
{
    /**
     *
     */
    const FORMAT = 'H:i:sP';

    /**
     * @param DateTimeImmutable $date
     */
    public function __construct(DateTimeImmutable $date)
    {
        parent::__construct();
        $this->appendChild($this->createElement('xs:time', $date->format(self::FORMAT)));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->documentElement->nodeValue;
    }
}
