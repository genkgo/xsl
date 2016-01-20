<?php
namespace Genkgo\Xsl\Util;

use DOMElement;
use Genkgo\Xsl\Schema\XmlSchema;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;

final class Assert
{
    /**
     * @param $value
     * @throws InvalidArgumentException
     */
    public static function assertArray($value)
    {
        if (is_array($value) === false) {
            throw new InvalidArgumentException("Expected a date object, got scalar");
        }
    }

    /**
     * @param DOMElement $element
     * @param $name
     * @throws InvalidArgumentException
     */
    public static function assertSchema(DOMElement $element, $name)
    {
        if ($element->namespaceURI !== XmlSchema::URI || $element->localName !== $name) {
            $nsSchema = XmlSchema::URI;
            throw new InvalidArgumentException("Expected a {$nsSchema}:{$name} object, got {$element->nodeName}");
        }
    }
}
