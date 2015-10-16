<?php
namespace Genkgo\Xsl\Schema;

/**
 * Class XsSequence
 * @package Genkgo\Xsl\Schema
 */
final class XsSequence extends AbstractXsElement
{
    /**
     * @param array $list
     * @return XsSequence
     */
    public static function fromArray(array $list)
    {
        $sequence = new static;

        foreach ($list as $item) {
            $child = $sequence->createElementNs(XmlSchema::URI, 'xs:string', $item);
            $sequence->documentElement->appendChild($child);
        }

        return $sequence;
    }

    /**
     * @return string
     */
    protected function getElementName()
    {
        return 'sequence';
    }

}
