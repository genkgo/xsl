<?php
namespace Genkgo\Xsl\Util;

use DOMNode;
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
     * @param DOMNode $element
     * @param $name
     * @throws InvalidArgumentException
     */
    public static function assertSchema(DOMNode $element, $name)
    {
        if ($element->namespaceURI !== XmlSchema::URI || $element->localName !== $name) {
            $nsSchema = XmlSchema::URI;
            throw new InvalidArgumentException("Expected a {$nsSchema}:{$name} object, got {$element->nodeName}");
        }
    }
}
