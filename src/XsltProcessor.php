<?php
namespace Genkgo\Xsl;

use DOMDocument;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\Exception\CacheDisabledException;
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
     * @var array
     */
    private $phpFunctions = [];

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

        $transpiler = $this->createTranspiler($styleSheet);
        parent::importStylesheet($this->getTranspiledStyleSheet($transpiler, $styleSheet));

        $this->startTransformation($transpiler);
        $result = parent::transformToXml($doc);
        $this->stopTransformation($transpiler);

        return $result;
    }

    /**
     * @param \DOMNode $doc
     * @return DOMDocument
     */
    public function transformToDoc($doc)
    {
        $styleSheet = $this->styleSheetToDomDocument();

        $transpiler = $this->createTranspiler($styleSheet);
        parent::importStylesheet($this->getTranspiledStyleSheet($transpiler, $styleSheet));

        $this->startTransformation($transpiler);
        $result = parent::transformToDoc($doc);
        $this->stopTransformation($transpiler);

        return $result;
    }

    /**
     * @param DOMDocument|SimpleXMLElement $doc
     * @param string $uri
     * @return int
     */
    public function transformToUri($doc, $uri)
    {
        $styleSheet = $this->styleSheetToDomDocument();

        $transpiler = $this->createTranspiler($styleSheet);
        parent::importStylesheet($this->getTranspiledStyleSheet($transpiler, $styleSheet));

        $this->startTransformation($transpiler);
        $result = parent::transformToUri($doc, $uri);
        $this->stopTransformation($transpiler);

        return $result;
    }

    public function registerPHPFunctions ($restrict = null) {
        if (is_string($restrict)) {
            $this->phpFunctions = [$restrict];
        }

        $this->phpFunctions = $restrict;
    }

    /**
     * @param Transpiler $transpiler
     * @param DOMDocument $styleSheet
     * @return DOMDocument
     */
    private function getTranspiledStyleSheet(Transpiler $transpiler, DOMDocument $styleSheet)
    {
        $this->boot();

        $streamContext = stream_context_create($this->createStreamOptions($transpiler));
        libxml_set_streams_context($streamContext);

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
        $phpFunctions = $this->phpFunctions;

        if ($phpFunctions === null) {
            parent::registerPHPFunctions();
        } else {
            $phpFunctions[] = PhpCallback::class . '::call';
            $phpFunctions[] = PhpCallback::class . '::callContext';
            parent::registerPHPFunctions($phpFunctions);
        }

        $xpathCompiler = new Xpath\Compiler();
        $context = new TransformationContext($styleSheet, $xpathCompiler, $phpFunctions);
        $transpiler = new Transpiler($context);

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
            $home = $styleSheet->documentURI . '_';
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

    /**
     * @param Transpiler $transpiler
     */
    private function startTransformation(Transpiler $transpiler)
    {
        PhpCallback::attach($transpiler->context);
    }

    /**
     * @param Transpiler $transpiler
     */
    private function stopTransformation(Transpiler $transpiler)
    {
        PhpCallback::detach($transpiler->context);
    }

    /**
     * @param Transpiler $transpiler
     * @return array
     */
    private function createStreamOptions(Transpiler $transpiler)
    {
        $contextOptions = [
            'gxsl' => [
                'transpiler' => $transpiler
            ]
        ];

        try {
            $contextOptions['gxsl']['cache'] = $this->config->getCacheAdapter();
        } catch (CacheDisabledException $e) {
        }

        return $contextOptions;
    }
}
