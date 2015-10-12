<?php
namespace Genkgo\Xsl;

use DOMDocument;

interface TransformerInterface
{
    public function transform(DOMDocument $document);
}
