<?php
namespace Genkgo\Xsl\Xsl;

use DOMAttr;

interface AttributeTransformerInterface
{
    /**
     * @param DOMAttr $attribute
     * @return bool
     */
    public function supports (DOMAttr $attribute);

    /**
     * @param DOMAttr $attribute
     * @return void
     */
    public function transform(DOMAttr $attribute);
}