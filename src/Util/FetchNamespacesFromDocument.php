<?php
namespace Genkgo\Xsl\Util;

use DOMDocument;
use DOMXPath;

/**
 * Class FetchNamespacesFromDocument
 * @package Genkgo\Xsl\Util
 */
final class FetchNamespacesFromDocument
{
    /**
     * @param DOMDocument $document
     * @return array
     */
    public static function fetch(DOMDocument $document)
    {
        $namespaces = [];

        $listOfNamespaces = new DOMXPath($document);
        foreach ($listOfNamespaces->query('namespace::*') as $node) {
            $namespaces[$node->localName] = $node->namespaceURI;
        }

        return $namespaces;
    }
}
