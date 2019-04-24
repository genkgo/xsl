<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Schema;

use DOMDocument;
use Genkgo\Xsl\Exception\CastException;

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
            $root->nodeValue = $value;
        }
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

    /**
     * @param mixed $elements
     * @return mixed
     * @throws CastException
     */
    public static function castToNodeValue($elements)
    {
        if (\is_scalar($elements)) {
            return $elements;
        }

        if (\is_array($elements) && \count($elements) === 1) {
            return $elements[0]->nodeValue;
        }

        throw new CastException('Cannot convert list of elements to string');
    }
}
