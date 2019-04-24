<?php
declare(strict_types=1);

namespace Genkgo\Xsl;

use DOMDocument;

/**
 * Interface TransformerInterface
 */
interface TransformerInterface
{
    /**
     * @param DOMDocument $document
     * @return void
     */
    public function transform(DOMDocument $document);
}
