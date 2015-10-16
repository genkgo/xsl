<?php
namespace Genkgo\Xsl\Schema;

use DateTimeImmutable;
use DOMDocument;

final class XsDate extends DOMDocument {

    const FORMAT = 'Y-m-dP';

    /**
     * @param DateTimeImmutable $date
     */
    public function __construct (DateTimeImmutable $date = null) {
        parent::__construct();
        $this->appendChild($this->createElement('xs:date', $date->format(self::FORMAT)));
    }

    public function __toString () {
        return $this->documentElement->nodeValue;
    }

}