<?php
namespace Genkgo\Xsl\Xsl;

use DOMElement;
use Genkgo\Xsl\TransformationContext;

/**
 * Interface ElementTransformerInterface
 * @package Genkgo\Xsl\Xsl
 */
interface ElementTransformerInterface
{
    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element);
}
