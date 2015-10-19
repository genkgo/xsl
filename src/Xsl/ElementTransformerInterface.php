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
     * @param DOMElement $element
     * @return void
     */
    public function transform(DOMElement $element);
}
