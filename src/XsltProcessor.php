<?php
namespace Genkgo\Xsl;

use DOMDocument;
use SimpleXMLElement;
use XSLTProcessor as PhpXsltProcessor;

/**
 * Class XsltProcessor
 * @package Genkgo\Xsl
 */
class XsltProcessor extends PhpXsltProcessor
{
    /**
     * @var bool
     */
    private static $booted = false;
    /**
     * @var
     */
    private $styleSheet;

    /**
     * @param object $stylesheet
     */
    public function importStyleSheet($stylesheet)
    {
        $this->styleSheet = $stylesheet;
    }

    /**
     * @param DOMDocument|SimpleXMLElement $doc
     * @return string
     */
    public function transformToXML($doc)
    {
        $styleSheet = $this->styleSheet;

        if ($this->styleSheet instanceof SimpleXMLElement) {
            $styleSheet = dom_import_simplexml($this->styleSheet)->ownerDocument;
        }

        parent::importStylesheet($this->getTranspiledStyleSheet($styleSheet));
        return parent::transformToXml($doc);
    }

    /**
     * @param \DOMNode $doc
     * @return DOMDocument
     */
    public function transformToDoc($doc)
    {
        $styleSheet = $this->styleSheet;

        if ($this->styleSheet instanceof SimpleXMLElement) {
            $styleSheet = dom_import_simplexml($this->styleSheet)->ownerDocument;
        }

        parent::importStylesheet($this->getTranspiledStyleSheet($styleSheet));
        return parent::transformToDoc($doc);
    }

    /**
     * @param DOMDocument|SimpleXMLElement $doc
     * @param string $uri
     * @return int
     */
    public function transformToUri($doc, $uri)
    {
        $styleSheet = $this->styleSheet;

        if ($this->styleSheet instanceof SimpleXMLElement) {
            $styleSheet = dom_import_simplexml($this->styleSheet)->ownerDocument;
        }

        parent::importStylesheet($this->getTranspiledStyleSheet($styleSheet));
        return parent::transformToUri($doc, $uri);
    }

    /**
     * @param DOMDocument $styleSheet
     * @return DOMDocument
     */
    private function getTranspiledStyleSheet(DOMDocument $styleSheet)
    {
        if (self::$booted === false) {
            stream_wrapper_register('gxsl', Stream::class);
            self::$booted = true;
        }

        $documentContext = new Context($styleSheet);

        $streamContext = stream_context_create([
            'gxsl' => [
                'documentContext' => $documentContext
            ]
        ]);
        libxml_set_streams_context($streamContext);

        $startRoot =  '<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">';
        $endRoot = '</xsl:stylesheet>';

        if (is_file($styleSheet->documentURI)) {
            $home = dirname($styleSheet->documentURI) . '/~';
        } else {
            $home = $styleSheet->documentURI . '/~';
        }

        $bootstrap = $startRoot . '<xsl:include href="gxsl://' . $home . '" />' . $endRoot;

        $transpiledStyleSheet = new DOMDocument('1.0', 'UTF-8');
        $transpiledStyleSheet->loadXML($bootstrap);

        return $transpiledStyleSheet;
    }
}
