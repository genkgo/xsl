<?php
namespace Genkgo\Xsl\Xsl;

use DOMElement;
use Genkgo\Xsl\DocumentContext;

/**
 * Interface ElementTransformerInterface
 * @package Genkgo\Xsl\Xsl
 */
interface ElementTransformerInterface
{
    /**
     * @param DOMElement $element
     * @param DocumentContext $context
     * @return void
     */
    public function transform(DOMElement $element, DocumentContext $context);
}
