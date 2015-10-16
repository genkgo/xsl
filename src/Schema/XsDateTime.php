<?php
namespace Genkgo\Xsl\Schema;

use DateTimeImmutable;
use DOMDocument;

/**
 * Class XsDateTime
 * @package Genkgo\Xsl\Schema
 */
final class XsDateTime extends DOMDocument
{
    /**
     *
     */
    const FORMAT = 'Y-m-d H:i:sP';

    /**
     * @param DateTimeImmutable $date
     */
    public function __construct(DateTimeImmutable $date)
    {
        parent::__construct();
        $this->appendChild($this->createElement('xs:dateTime', $date->format(self::FORMAT)));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->documentElement->nodeValue;
    }
}
