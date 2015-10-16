<?php
namespace Genkgo\Xsl\Schema;

use DOMDocument;

/**
 * Class AbstractXsElement
 * @package Genkgo\Xsl\Schema
 */
abstract class AbstractXsElement extends DOMDocument {

    /**
     * @param null $value
     */
    final public function __construct ($value = null) {
        parent::__construct('1.0', 'UTF-8');

        $root = $this->createElementNS(XmlSchema::URI, 'xs:' . $this->getElementName());
        $root->nodeValue = $value;
        $this->appendChild($root);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->documentElement->nodeValue;
    }

    /**
     * @return string
     */
    abstract protected function getElementName();
}