<?php
namespace Genkgo\Xsl\Xsl;

use DOMElement;
use Genkgo\Xsl\DocumentContext;

interface ElementTransformerInterface {

    public function transform (DOMElement $element, DocumentContext $context);

}