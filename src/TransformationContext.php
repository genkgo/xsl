<?php
namespace Genkgo\Xsl;

use DOMDocument;

/**
 * Class Context
 * @package Genkgo\Xsl
 */
class TransformationContext
{
    /**
     * @var DOMDocument
     */
    private $document;

    /**
     * @param DOMDocument $document
     * @param Xpath\Compiler $compiler
     */
    public function __construct(DOMDocument $document, Xpath\Compiler $compiler)
    {
        $this->document = $document;
        $this->xpathCompiler = $compiler;
    }

    /**
     * @return DOMDocument
     */
    public function getDocument()
    {
        return $this->document;
    }


}
