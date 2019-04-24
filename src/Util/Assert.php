<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Util;

use DOMNode;
use Genkgo\Xsl\Schema\XmlSchema;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;

final class Assert
{
    /**
     * @param mixed $value
     * @throws InvalidArgumentException
     */
    public static function assertArray($value): void
    {
        if (\is_array($value) === false) {
            throw new InvalidArgumentException("Expected a date object, got scalar");
        }
    }

    /**
     * @param DOMNode $element
     * @param string $name
     * @throws InvalidArgumentException
     */
    public static function assertSchema(DOMNode $element, string $name): void
    {
        if ($element->namespaceURI !== XmlSchema::URI || $element->localName !== $name) {
            $nsSchema = XmlSchema::URI;
            throw new InvalidArgumentException("Expected a {$nsSchema}:{$name} object, got {$element->nodeName}");
        }
    }
}
