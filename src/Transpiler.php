<?php
declare(strict_types=1);

namespace Genkgo\Xsl;

use Closure;
use DOMDocument;
use Genkgo\Xsl\Callback\PhpCallback;
use Psr\SimpleCache\CacheInterface;

final class Transpiler
{
    /**
     * @var TransformationContext
     */
    private $context;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param TransformationContext $context
     * @param CacheInterface $cache
     */
    public function __construct(
        TransformationContext $context,
        CacheInterface $cache
    ) {
        $this->context = $context;
        $this->cache = $cache;
    }

    /**
     * @return string
     */
    public function transpileRoot()
    {
        $document = $this->context->getDocument();

        $documentURI = $document->documentURI;

        // @codeCoverageIgnoreStart
        if (PHP_OS === 'WINNT') {
            $documentURI = \ltrim(\str_replace('file:', '', $documentURI), '/');
        }
        // @codeCoverageIgnoreEnd

        if (!$documentURI || !\is_file($documentURI)) {
            $this->transpile($document);
            return $document->saveXML();
        }

        $result = $this->cache->get($documentURI, '');
        if ($result === '') {
            if ($document->doctype instanceof \DOMDocumentType && $document->doctype->entities->length > 0) {
                throw new \DOMException('Invalid document, contains entities');
            }

            $this->transpile($document);
            $result = $document->saveXML();
            $this->cache->set($documentURI, $result);
        }

        return $result;
    }

    /**
     * @param DOMDocument $document
     */
    public function transpile(DOMDocument $document)
    {
        $root = $document->documentElement;
        if ($root === null) {
            return;
        }

        $transformers = $this->context->getTransformers();
        foreach ($transformers as $transformer) {
            $transformer->transform($document);
        }

        if ($root->getAttribute('version') !== '1.0') {
            $root->setAttribute('version', '1.0');
        }
    }

    /**
     * @param string $path
     * @return string
     */
    public function transpileFile(string $path): string
    {
        $result = $this->cache->get($path, '');
        if ($result === '') {
            $document = new DOMDocument();
            $document->substituteEntities = false;
            $document->resolveExternals = false;
            $document->load($path);

            if ($document->doctype instanceof \DOMDocumentType && $document->doctype->entities->length > 0) {
                throw new \DOMException('Invalid document, contains entities');
            }

            $this->transpile($document);
            $result = $document->saveXML();
            $this->cache->set($path, $result);
        }

        return $result;
    }

    /**
     * @param Closure $nativeTransformation
     * @return mixed
     */
    public function transform(Closure $nativeTransformation)
    {
        PhpCallback::set($this->context);

        $result = $nativeTransformation();

        PhpCallback::reset();
        return $result;
    }
}
