<?php
namespace Genkgo\Xsl;

use DOMDocument;
use DOMElement;

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
     * @var Xpath\Compiler
     */
    private $xpathCompiler;
    /**
     * @var array|null
     */
    private $phpFunctions;
    /**
     * @var array
     */
    private $elementContext = [];

    /**
     * @param DOMDocument $document
     * @param Xpath\Compiler $compiler
     * @param array $phpFunctions
     */
    public function __construct(DOMDocument $document, Xpath\Compiler $compiler, array $phpFunctions = null)
    {
        $this->document = $document;
        $this->xpathCompiler = $compiler;
        $this->phpFunctions = $phpFunctions;
    }

    /**
     * @return DOMDocument
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @return Xpath\Compiler
     */
    public function getXpathCompiler()
    {
        return $this->xpathCompiler;
    }

    /**
     * @return array|null
     */
    public function getPhpFunctions()
    {
        return $this->phpFunctions;
    }

    public function setElementContext(DOMElement $element, array $context)
    {
        $objectHash = spl_object_hash($element);
        $this->elementContext[$objectHash] = $context;

        $element->setAttribute('data-object-hash', $objectHash);
    }

    /**
     * @param DOMElement $element
     * @return array
     */
    public function getElementContextFor(DOMElement $element)
    {
        $objectHash = $element->getAttribute('data-object-hash');

        if (isset($this->elementContext[$objectHash])) {
            return $this->elementContext[$objectHash];
        }

        return null;
    }

}
