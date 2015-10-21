<?php
namespace Genkgo\Xsl\Xsl;

use DOMElement;

/**
 * Interface ElementTransformerInterface
 * @package Genkgo\Xsl\Xsl
 */
interface ElementTransformerInterface
{
    /**
     * @param DOMElement $document
     * @return bool
     */
    public function supports (DOMElement $document);
    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element);
}
