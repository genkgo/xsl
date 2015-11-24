<?php
namespace Genkgo\Xsl\Util;

use DOMNode;
use DOMXPath;

/**
 * Class FetchNamespacesFromDocument
 * @package Genkgo\Xsl\Util
 */
final class FetchNamespacesFromNode
{
    /**
     * @param DOMNode $element
     * @return array
     */
    public static function fetch(DOMNode $element)
    {
        $namespaces = [];

        $listOfNamespaces = new DOMXPath($element->ownerDocument);
        foreach ($listOfNamespaces->query('namespace::*', $element) as $node) {
            $namespaces[$node->localName] = $node->namespaceURI;
        }

        return $namespaces;
    }
}
