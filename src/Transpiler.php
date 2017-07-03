<?php
namespace Genkgo\Xsl;

use Closure;
use DOMDocument;
use Genkgo\Cache\CallbackCacheInterface;
use Genkgo\Xsl\Callback\PhpCallback;

/**
 * Class Transpiler
 * @package Genkgo\Xsl
 */
final class Transpiler
{
    /**
     * @var TransformationContext
     */
    private $context;
    /**
     * @var CallbackCacheInterface
     */
    private $cacheAdapter;

    /**
     * @param TransformationContext $context
     * @param CallbackCacheInterface $cacheAdapter
     */
    public function __construct(TransformationContext $context, CallbackCacheInterface $cacheAdapter = null)
    {
        $this->context = $context;
        $this->cacheAdapter = $cacheAdapter;
    }

    /**
     * @return DOMDocument
     */
    public function transpileRoot()
    {
        $document = $this->context->getDocument();

        $callback = function () use ($document) {
            $this->transpile($document);
            return $document->saveXML();
        };

        $documentURI = $document->documentURI;
        // @codeCoverageIgnoreStart
        if (PHP_OS === 'WINNT') {
            $documentURI = ltrim(str_replace('file:', '', $documentURI), '/');
        }
        // @codeCoverageIgnoreEnd
        
        if ($this->cacheAdapter !== null && is_file($documentURI)) {
            return $this->cacheAdapter->get($documentURI, $callback);
        }

        return $callback();
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


        if ($root && $root->getAttribute('version') !== '1.0') {
            $root->setAttribute('version', '1.0');
        }
    }

    /**
     * @param $path
     * @return DOMDocument
     */
    public function transpileFile($path)
    {
        $callback = function () use ($path) {
            $document = new DOMDocument();
            $document->load($path);
            $this->transpile($document);
            return $document->saveXML();
        };

        if ($this->cacheAdapter !== null) {
            return $this->cacheAdapter->get($path, $callback);
        }

        return $callback();
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
