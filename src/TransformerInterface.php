<?php
namespace Genkgo\Xsl;

use DOMDocument;

/**
 * Interface TransformerInterface
 * @package Genkgo\Xsl
 */
interface TransformerInterface
{
    /**
     * @param DOMDocument $document
     * @param TransformationContext $transformationContext
     * @return void
     */
    public function transform(DOMDocument $document, TransformationContext $transformationContext);
}
