<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Schema;

use DOMDocument;

abstract class AbstractXsElement extends DOMDocument
{
    /**
     * @param mixed|null $value
     */
    final public function __construct($value = null)
    {
        parent::__construct('1.0', 'UTF-8');

        $root = $this->createElementNS(XmlSchema::URI, 'xs:' . $this->getElementName());
        if ($value !== null) {
            $root->nodeValue = (string)$value;
        }
        $this->appendChild($root);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->documentElement->nodeValue;
    }

    /**
     * @return string
     */
    abstract protected function getElementName(): string;
}
