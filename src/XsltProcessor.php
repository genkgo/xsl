<?php
namespace Genkgo\Xsl;

use DOMDocument;
use DOMNode;
use SimpleXMLElement;
use XSLTProcessor as PhpXsltProcessor;

/**
 * Class XsltProcessor
 * @package Genkgo\Xsl
 */
class XsltProcessor extends PhpXsltProcessor
{

    public function __construct () {
    }

    /**
     * @param DOMNode $doc
     * @return DOMDocument|bool
     */
    public function transformToDoc($doc)
    {
        return parent::transformToDoc($doc);
    }

    /**
     * @param DOMDocument $doc
     * @param string $uri
     * @return int|bool
     */
    public function transformToUri($doc, $uri)
    {
        return parent::transformToUri($doc, $uri);
    }

    /**
     * @param DOMDocument|SimpleXMLElement $doc
     * @return string|bool
     */
    public function transformToXML($doc)
    {
        return parent::transformToXML($doc);
    }
}