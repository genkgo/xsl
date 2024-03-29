<?php
declare(strict_types=1);

namespace Genkgo\Xsl;

use Genkgo\Xsl\Callback\FunctionCollection;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\Exception\TransformationException;
use Genkgo\Xsl\Util\TransformerCollection;
use Genkgo\Xsl\Xsl\Transformer;
use Psr\SimpleCache\CacheInterface;
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
     * @var bool
     */
    private $excludeAllNamespaces;

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

    public function excludeAllPrefixes(): void
    {
        $this->excludeAllNamespaces = true;
    }

    public function importStylesheet(object $stylesheet): bool
    {
        $this->styleSheet = $stylesheet;
        return true;
    }

    /**
     * @param \DOMDocument|\SimpleXMLElement $doc
     * @return string
     */
    public function transformToXML($doc):? string
    {
        $styleSheet = $this->styleSheetToDomDocument();

        $transpiler = $this->createTranspiler($styleSheet);

        return $this->catchLibXmlErrorThrowTransformException(
            function () use ($doc, $styleSheet, $transpiler):? string {
                parent::importStylesheet($this->getTranspiledStyleSheet($transpiler, $styleSheet));
                $this->throwOnLibxmlError();

                return $transpiler->transform(function () use ($doc) {
                    $result = parent::transformToXml($doc);
                    $this->throwOnLibxmlError();
                    return $result;
                });
            }
        );
    }

    /**
     * @param object|\DOMDocument $document
     */
    public function transformToDoc(object $document, ?string $returnClass = null): \DOMDocument|false
    {
        $styleSheet = $this->styleSheetToDomDocument();

        $transpiler = $this->createTranspiler($styleSheet);

        return $this->catchLibXmlErrorThrowTransformException(
            function () use ($document, $returnClass, $styleSheet, $transpiler):? \DOMDocument {
                parent::importStylesheet($this->getTranspiledStyleSheet($transpiler, $styleSheet));
                $this->throwOnLibxmlError();

                return $transpiler->transform(function () use ($document, $returnClass) {
                    $result = parent::transformToDoc($document, $returnClass);
                    $this->throwOnLibxmlError();
                    return $result;
                });
            }
        );
    }

    /**
     * @param object|\DOMDocument|\SimpleXMLElement $document
     */
    public function transformToUri(object $document, string $uri): int
    {
        $styleSheet = $this->styleSheetToDomDocument();
        $transpiler = $this->createTranspiler($styleSheet);

        return $this->catchLibXmlErrorThrowTransformException(
            function () use ($document, $uri, $styleSheet, $transpiler):? int {
                parent::importStylesheet($this->getTranspiledStyleSheet($transpiler, $styleSheet));
                $this->throwOnLibxmlError();

                return $transpiler->transform(function () use ($document, $uri) {
                    $result = parent::transformToUri($document, $uri);
                    $this->throwOnLibxmlError();
                    return $result;
                });
            }
        );
    }

    /**
     * @param string|array|null $restrict
     */
    public function registerPHPFunctions($restrict = null): void
    {
        if (\is_string($restrict)) {
            $restrict = [$restrict];
        }

        $this->phpFunctions = $restrict;
    }

    /**
     * @param Transpiler $transpiler
     * @param \DOMDocument $styleSheet
     * @return \DOMDocument
     */
    private function getTranspiledStyleSheet(Transpiler $transpiler, \DOMDocument $styleSheet): \DOMDocument
    {
        $this->boot();

        $streamContext = \stream_context_create($this->createStreamOptions($transpiler));
        \libxml_set_streams_context($streamContext);

        return $this->createTranspiledDocument($styleSheet);
    }

    private function boot(): void
    {
        if (self::$booted === false) {
            \stream_wrapper_register('gxsl', Stream::class);
            self::$booted = true;
        }
    }

    /**
     * @param \DOMDocument $styleSheet
     * @return Transpiler
     */
    private function createTranspiler(\DOMDocument $styleSheet): Transpiler
    {
        $phpFunctions = $this->phpFunctions;

        if ($phpFunctions === null) {
            parent::registerPHPFunctions();
        } else {
            $phpFunctions[] = PhpCallback::class . '::call';
            $phpFunctions[] = PhpCallback::class . '::callStatic';
            parent::registerPHPFunctions($phpFunctions);
        }

        $transformers = new TransformerCollection();
        $functions = new FunctionCollection();

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
    private function getNamespaces(Xpath\Compiler $xpathCompiler): array
    {
        $defaultExcludedPrefixes = $this->excludeAllNamespaces ? ['#all'] : Transformer::DEFAULT_EXCLUDE_PREFIXES;

        return \array_merge(
            $this->extensions,
            [
                new Xsl\XslTransformations($xpathCompiler, $defaultExcludedPrefixes),
                new Xpath\XmlPath(),
                new Schema\XmlSchema()
            ]
        );
    }

    /**
     * @param \DOMDocument $styleSheet
     * @return \DOMDocument
     */
    private function createTranspiledDocument(\DOMDocument $styleSheet): \DOMDocument
    {
        $documentURI = $styleSheet->documentURI;

        // @codeCoverageIgnoreStart
        if (PHP_OS === 'WINNT') {
            $documentURI = \str_replace('file:', '/', $documentURI);
        }
        // @codeCoverageIgnoreEnd

        $transpiledStyleSheet = new \DOMDocument('1.0', 'UTF-8');
        $transpiledStyleSheet->load(Stream::PROTOCOL . Stream::HOST . $documentURI . Stream::ROOT);
        return $transpiledStyleSheet;
    }

    /**
     * @return \DOMDocument
     */
    private function styleSheetToDomDocument(): \DOMDocument
    {
        if ($this->styleSheet instanceof \SimpleXMLElement) {
            $document = \dom_import_simplexml($this->styleSheet);

            /** @phpstan-ignore-next-line */
            if (!$document instanceof \DOMDocument === false) {
                throw new \UnexpectedValueException('Cannot transform SimpleXMLElement to DOMDocument');
            }

            return $document->ownerDocument;
        }

        if ($this->styleSheet instanceof \DOMDocument) {
            return $this->styleSheet;
        }

        throw new \UnexpectedValueException('Unknown stylesheet type');
    }

    /**
     * @param Transpiler $transpiler
     * @return array
     */
    private function createStreamOptions(Transpiler $transpiler): array
    {
        $contextOptions = [
            'gxsl' => [
                'transpiler' => $transpiler
            ]
        ];

        return $contextOptions;
    }

    private function throwOnLibxmlError(): void
    {
        $errors = \libxml_get_errors();
        if ($errors === []) {
            return;
        }

        throw TransformationException::fromLibxmlErrorList($errors);
    }

    private function catchLibXmlErrorThrowTransformException(\Closure $closure)
    {
        $useInternalErrors = \libxml_use_internal_errors(false);

        \set_error_handler(
            function ($number, $message) {
                if (\error_reporting() === 0) {
                    return false;
                }

                throw new TransformationException('Transformation failed: ' . $message, $number);
            }
        );

        try {
            return $closure();
        } catch (\Throwable $e) {
            throw new TransformationException('Transformation failed: ' . $e->getMessage(), 0, $e);
        } finally {
            \libxml_clear_errors();
            \libxml_use_internal_errors($useInternalErrors);
            \restore_error_handler();
        }
    }
}
