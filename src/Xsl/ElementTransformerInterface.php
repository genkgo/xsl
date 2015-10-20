<?php
namespace Genkgo\Xsl\Xsl;

use DOMDocument;
use DOMElement;
use Genkgo\Xsl\TransformationContext;

/**
 * Interface ElementTransformerInterface
 * @package Genkgo\Xsl\Xsl
 */
interface ElementTransformerInterface
{
    /**
     * @param DOMDocument $document
     * @return bool
     */
    public function supports (DOMDocument $document);
    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element);
}
