<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl;

use DOMElement;

interface ElementTransformerInterface
{
    /**
     * @param DOMElement $document
     * @return bool
     */
    public function supports(DOMElement $document): bool;

    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element): void;
}
