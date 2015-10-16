<?php
namespace Genkgo\Xsl\Xsl;

use DOMElement;
use Genkgo\Xsl\Context;

interface ElementTransformerInterface {

    public function transform (DOMElement $element, Context $context);

}