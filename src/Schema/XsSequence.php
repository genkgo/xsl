<?php
namespace Genkgo\Xsl\Schema;

use DOMDocument;

/**
 * Class XsSequence
 * @package Genkgo\Xsl\Schema
 */
final class XsSequence extends DOMDocument
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->appendChild($this->createElement('xs:sequence'));
    }

    /**
     * @param array $list
     * @return XsSequence
     */
    public static function fromArray(array $list)
    {
        $sequence = new static;

        foreach ($list as $item) {
            $child = $sequence->createElement('xs:string', $item);
            $sequence->documentElement->appendChild($child);
        }

        return $sequence;
    }
}
