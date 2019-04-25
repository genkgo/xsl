<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Util;

use DOMDocument;
use DOMNode;
use DOMXPath;

final class FetchNamespacesFromNode
{
    /**
     * @param DOMNode $element
     * @return array
     */
    public static function fetch(DOMNode $element): array
    {
        $namespaces = [];

        if ($element->ownerDocument instanceof DOMDocument === false) {
            throw new \InvalidArgumentException('Expecting DOMNode to be attached to document');
        }

        $listOfNamespaces = new DOMXPath($element->ownerDocument);
        foreach ($listOfNamespaces->query('namespace::*', $element) as $node) {
            $namespaces[$node->localName] = $node->namespaceURI;
        }

        return $namespaces;
    }
}
