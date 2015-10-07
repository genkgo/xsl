<?php
namespace Genkgo\Xsl;

use DOMDocument;

/**
 * Class Context
 * @package Genkgo\Xsl
 */
class Context
{
    /**
     * @var DOMDocument
     */
    private $document;

    /**
     * @param DOMDocument $document
     */
    public function __construct(DOMDocument $document)
    {
        $this->document = $document;
    }

    /**
     * @return DOMDocument
     */
    public function getDocument()
    {
        return $this->document;
    }
}
