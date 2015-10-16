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
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct (Config $config = null) {
        if ($config === null) {
            $config = Config::fromDefault();
        }

        $this->config = $config;
    }

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
        $styleSheet = $this->styleSheetToDomDocument();

        parent::importStylesheet($this->getTranspiledStyleSheet($styleSheet));
        return parent::transformToXml($doc);
    }

    /**
     * @param \DOMNode $doc
     * @return DOMDocument
     */
    public function transformToDoc($doc)
    {
        $styleSheet = $this->styleSheetToDomDocument();

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
        $styleSheet = $this->styleSheetToDomDocument();

        parent::importStylesheet($this->getTranspiledStyleSheet($styleSheet));
        return parent::transformToUri($doc, $uri);
    }

    /**
     * @param DOMDocument $styleSheet
     * @return DOMDocument
     */
    private function getTranspiledStyleSheet(DOMDocument $styleSheet)
    {
        $this->boot();
        $transpiler = $this->createTranspiler($styleSheet);

        $streamContext = stream_context_create([
            'gxsl' => [
                'transpiler' => $transpiler
            ]
        ]);
        libxml_set_streams_context($streamContext);

        $this->registerPHPFunctions();
        return $this->createTranspiledDocument($styleSheet);
    }

    /**
     *
     */
    private function boot () {
        if (self::$booted === false) {
            stream_wrapper_register('gxsl', Stream::class);
            self::$booted = true;
        }
    }

    /**
     * @param DOMDocument $styleSheet
     * @return Transpiler
     */
    private function createTranspiler (DOMDocument $styleSheet) {
        $xpathCompiler = new Xpath\Compiler();
        $transpiler = new Transpiler(new Context($styleSheet));

        $namespaces = $this->getNamespaces();
        foreach ($namespaces as $namespace) {
            $namespace->registerXpathFunctions($xpathCompiler);
            $namespace->registerTransformers($transpiler, $xpathCompiler);
        }

        return $transpiler;
    }

    /**
     * @return XmlNamespaceInterface[]
     */
    private function getNamespaces () {
        if ($this->config->shouldUpgradeToXsl2()) {
            $namespaces = [
                new Schema\XmlSchema(),
                new Xsl\XslTransformations(),
                new Xpath\XmlPath()
            ];
        } else {
            $namespaces = [];
        }

        return array_merge($namespaces, $this->config->getExtensions());
    }

    /**
     * @param DOMDocument $styleSheet
     * @return DOMDocument
     */
    private function createTranspiledDocument(DOMDocument $styleSheet)
    {
        if (is_file($styleSheet->documentURI)) {
            $home = dirname($styleSheet->documentURI) . '/~';
        } else {
            $home = $styleSheet->documentURI . '/~';
        }

        $transpiledStyleSheet = new DOMDocument('1.0', 'UTF-8');
        $transpiledStyleSheet->load('gxsl://' . $home);
        return $transpiledStyleSheet;
    }

    /**
     * @return DOMDocument
     */
    private function styleSheetToDomDocument()
    {
        if ($this->styleSheet instanceof SimpleXMLElement) {
            return dom_import_simplexml($this->styleSheet)->ownerDocument;
        }

        return $this->styleSheet;
    }
}
