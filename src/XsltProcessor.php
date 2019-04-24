<?php
declare(strict_types=1);

namespace Genkgo\Xsl;

use DOMDocument;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Util\TransformerCollection;
use Psr\SimpleCache\CacheInterface;
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
     * @var array|null
     */
    private $phpFunctions = [];

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var array|XmlNamespaceInterface[]
     */
    private $extensions;

    /**
     * @param CacheInterface $cache
     * @param array|XmlNamespaceInterface[] $extensions
     */
    public function __construct(CacheInterface $cache, array $extensions = [])
    {
        $this->cache = $cache;
        $this->extensions = $extensions;
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

        return new Transpiler($context, $this->cache);
    }

    /**
     * @param Xpath\Compiler $xpathCompiler
     * @return XmlNamespaceInterface[]
     */
    private function getNamespaces($xpathCompiler)
    {
        return \array_merge(
            $this->extensions,
            [
                new Xsl\XslTransformations($xpathCompiler),
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
