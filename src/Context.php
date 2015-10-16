<?php
namespace Genkgo\Xsl;

use DOMDocument;

/**
 * Class Context
 * @package Genkgo\Xsl
 */
class Context
{
    private $namespaces;

    /**
     * @var DOMDocument
     */
    private $document;

    /**
     * @param DOMDocument $document
     */
    public function __construct(DOMDocument $document)
    {
        $this->document = $document;
    }

    /**
     * @return DOMDocument
     */
    public function getDocument()
    {
        return $this->document;
    }

    public function setNamespaces($namespaces)
    {
        $this->namespaces = $namespaces;
    }

    public function getNamespace($localName)
    {
        return $this->namespaces[$localName];
    }
}
