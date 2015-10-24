<?php
namespace Genkgo\Xsl\Schema;

use DOMDocument;
use DOMNode;
use Genkgo\Xsl\Schema\Exception\UnknownSequenceItemException;

/**
 * Class XsSequence
 * @package Genkgo\Xsl\Schema
 */
final class XsSequence extends AbstractXsElement
{
    /**
     * @param array $list
     * @return XsSequence
     * @throws UnknownSequenceItemException
     */
    public static function fromArray(array $list)
    {
        /** @var DOMDocument $sequence */
        $sequence = new self;

        foreach ($list as $item) {
            if (is_scalar($item)) {
                $child = $sequence->createElementNs(XmlSchema::URI, 'xs:item', $item);
                $child->setAttribute('type', gettype($item));
                $sequence->documentElement->appendChild($child);
            } elseif ($item instanceof DOMNode && $item->prefix === 'xs') {
                $child = $sequence->importNode($item, true);
                $sequence->documentElement->appendChild($child);
            } else {
                // @codeCoverageIgnoreStart
                throw new UnknownSequenceItemException();
                // @codeCoverageIgnoreEnd
            }
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
