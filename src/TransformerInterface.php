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
     * @return void
     */
    public function transform(DOMDocument $document);
}
