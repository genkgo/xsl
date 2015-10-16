<?php
namespace Genkgo\Xsl\Schema;

use DateTimeImmutable;
use DOMDocument;

/**
 * Class XsDate
 * @package Genkgo\Xsl\Schema
 */
final class XsDate extends DOMDocument
{
    /**
     *
     */
    const FORMAT = 'Y-m-dP';

    /**
     * @param DateTimeImmutable $date
     */
    public function __construct(DateTimeImmutable $date = null)
    {
        parent::__construct();
        $this->appendChild($this->createElement('xs:date', $date->format(self::FORMAT)));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->documentElement->nodeValue;
    }
}
