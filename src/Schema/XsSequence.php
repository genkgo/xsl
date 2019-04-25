<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Schema;

use DOMAttr;
use DOMElement;

final class XsSequence extends AbstractXsElement
{
    /**
     * @param array $list
     * @return XsSequence
     */
    public static function fromArray(array $list)
    {
        $sequence = new self;

        foreach ($list as $item) {
            if (\is_scalar($item)) {
                // do not use nodeValue
                // https://bugs.php.net/bug.php?id=31613
                $child = $sequence->createElementNs(XmlSchema::URI, 'xs:item');
                $child->setAttribute('type', \gettype($item));
                $child->textContent = (string)$item;
                $sequence->documentElement->appendChild($child);
            } elseif ($item instanceof DOMElement) {
                $child = $sequence->importNode($item, true);
                $sequence->documentElement->appendChild($child);
            } elseif ($item instanceof DOMAttr) {
                $child = $sequence->createElementNs(XmlSchema::URI, 'xs:item', $item->nodeValue);
                $child->setAttribute('type', \gettype($item));
                $sequence->documentElement->appendChild($child);
            } else {
                // @codeCoverageIgnoreStart
                throw new \InvalidArgumentException('Cannot construct sequence from array');
                // @codeCoverageIgnoreEnd
            }
        }

        return $sequence;
    }

    /**
     * @return string
     */
    protected function getElementName(): string
    {
        return 'sequence';
    }
}
