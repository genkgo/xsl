<?php
namespace Genkgo\Xsl\Schema;

use Closure;
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
     * @param callable $callback
     * @return XsSequence
     * @throws UnknownSequenceItemException
     */
    public static function fromArray(array $list, Closure $callback = null)
    {
        $sequence = new static;

        foreach ($list as $item) {
            if (is_scalar($item)) {
                $child = $sequence->createElementNs(XmlSchema::URI, 'xs:string', $item);
                $sequence->documentElement->appendChild($child);
            } elseif ($item instanceof DOMNode) {
                $child = $sequence->importNode($item, true);
                $sequence->documentElement->appendChild($child);
            } else {
                throw new UnknownSequenceItemException();
            }

            if ($callback) {
                $callback($child, $item);
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
