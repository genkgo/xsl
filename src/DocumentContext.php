<?php
namespace Genkgo\Xsl;

class DocumentContext {

    /**
     * @var
     */
    private $namespaces;

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