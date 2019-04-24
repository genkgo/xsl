<?php
declare(strict_types=1);

namespace Genkgo\Xsl;

use DOMDocument;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\Exception\CacheDisabledException;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Util\TransformerCollection;
use SimpleXMLElement;
use XSLTProcessor as PhpXsltProcessor;

final class XsltProcessor extends PhpXsltProcessor
{
    /**
     * @var bool
     */
    private static $booted = false;

    /**
     * @var object
     */
    private $styleSheet;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var array|null
     */
    private $phpFunctions = [];

    /**
     * @param Config $config
     */
    public function __construct(Config $config = null)
    {
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
        \libxml_use_internal_errors();
        parent::importStylesheet($this->getTranspiledStyleSheet($transpiler, $styleSheet));

        return $transpiler->transform(function () use ($doc) {
            return parent::transformToXml($doc);
        });
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

        return $transpiler->transform(function () use ($doc) {
            return parent::transformToDoc($doc);
        });
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

        return $transpiler->transform(function () use ($doc, $uri) {
            return parent::transformToUri($doc, $uri);
        });
    }

    /**
     * @param string|array|null $restrict
     */
    public function registerPHPFunctions($restrict = null)
    {
        if (\is_string($restrict)) {
            $restrict = [$restrict];
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

        $streamContext = \stream_context_create($this->createStreamOptions($transpiler));
        \libxml_set_streams_context($streamContext);

        return $this->createTranspiledDocument($styleSheet);
    }
    
    private function boot()
    {
        if (self::$booted === false) {
            \stream_wrapper_register('gxsl', Stream::class);
            self::$booted = true;
        }
    }

    /**
     * @param DOMDocument $styleSheet
     * @return Transpiler
     */
    private function createTranspiler(DOMDocument $styleSheet)
    {
        $phpFunctions = $this->phpFunctions;

        if ($phpFunctions === null) {
            parent::registerPHPFunctions();
        } else {
            $phpFunctions[] = PhpCallback::class . '::call';
            $phpFunctions[] = PhpCallback::class . '::callStatic';
            $phpFunctions[] = PhpCallback::class . '::callContext';
            parent::registerPHPFunctions($phpFunctions);
        }

        $transformers = new TransformerCollection();
        $functions = new FunctionMap();

        $xpathCompiler = new Xpath\Compiler($functions);
        $context = new TransformationContext($styleSheet, $transformers, $functions, $phpFunctions);

        $namespaces = $this->getNamespaces($xpathCompiler);
        foreach ($namespaces as $namespace) {
            $namespace->register($transformers, $functions);
        }

        try {
            return new Transpiler($context, $this->config->getCacheAdapter(), $this->config->isEntitiesDisabled());
        } catch (CacheDisabledException $e) {
        }

        return new Transpiler($context, null, $this->config->isEntitiesDisabled());
    }

    /**
     * @param Xpath\Compiler $xpathCompiler
     * @return XmlNamespaceInterface[]
     */
    private function getNamespaces($xpathCompiler)
    {
        return \array_merge(
            $this->config->getExtensions(),
            [
                new Xsl\XslTransformations($xpathCompiler, $this->config),
                new Xpath\XmlPath(),
                new Schema\XmlSchema()
            ]
        );
    }

    /**
     * @param DOMDocument $styleSheet
     * @return DOMDocument
     */
    private function createTranspiledDocument(DOMDocument $styleSheet)
    {
        $documentURI = $styleSheet->documentURI;

        // @codeCoverageIgnoreStart
        if (PHP_OS === 'WINNT') {
            $documentURI = \str_replace('file:', '/', $documentURI);
        }
        // @codeCoverageIgnoreEnd

        $transpiledStyleSheet = new DOMDocument('1.0', 'UTF-8');
        $transpiledStyleSheet->load(Stream::PROTOCOL . Stream::HOST . $documentURI . Stream::ROOT);
        return $transpiledStyleSheet;
    }

    /**
     * @return DOMDocument
     */
    private function styleSheetToDomDocument()
    {
        if ($this->styleSheet instanceof SimpleXMLElement) {
            $document = \dom_import_simplexml($this->styleSheet);
            if ($document === false) {
                throw new \UnexpectedValueException('Cannot transform SimpleXMLElement to DOMDocument');
            }

            return $document->ownerDocument;
        }

        if ($this->styleSheet instanceof DOMDocument) {
            return $this->styleSheet;
        }

        throw new \UnexpectedValueException('Unknown stylesheet type');
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

        return $contextOptions;
    }
}
