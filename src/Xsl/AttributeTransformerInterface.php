<?php
namespace Genkgo\Xsl\Xsl;

use DOMAttr;
use DOMDocument;

interface AttributeTransformerInterface
{
    /**
     * @param DOMDocument $document
     * @return bool
     */
    public function supports (DOMDocument $document);

    /**
     * @param DOMAttr $attribute
     * @return void
     */
    public function transform(DOMAttr $attribute);
}