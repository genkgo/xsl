<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Schema;

final class DataTypeParser
{
    /**
     * @param \DOMNode $node
     * @return mixed
     */
    public function parse(\DOMNode $node)
    {
        if ($node->namespaceURI !== XmlSchema::URI) {
            throw new \InvalidArgumentException(
                "Expected a object with Xml Schema namespace, got {$node->namespaceURI}"
            );
        }

        switch ($node->localName) {
            case 'item':
                return $node->textContent;
            case 'integer':
                return XsInteger::parseNode($node);
            case 'date':
                return XsDate::parseNode($node);
            case 'time':
                return XsTime::parseNode($node);
            case 'dateTime':
                return XsDateTime::parseNode($node);
            case 'dayTimeDuration':
                return XsDayTimeDuration::parseNode($node);
            default:
                throw new \UnexpectedValueException('Cannot parse ' . $node->localName);
        }
    }
}
