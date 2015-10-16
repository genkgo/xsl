<?php
namespace Genkgo\Xsl;

use DOMDocument;

/**
 * Class Context
 * @package Genkgo\Xsl
 */
class Context
{
    /**
     * @var
     */
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

    /**
     * @param $namespaces|string[]
     */
    public function setNamespaces($namespaces)
    {
        $this->namespaces = $namespaces;
    }

    /**
     * @param $localName
     * @return string
     */
    public function getNamespace($localName)
    {
        return $this->namespaces[$localName];
    }
}
