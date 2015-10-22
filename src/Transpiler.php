<?php
namespace Genkgo\Xsl;

use Closure;
use DOMDocument;
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
     * @param TransformationContext $context
     */
    public function __construct(TransformationContext $context)
    {
        $this->context = $context;
    }

    /**
     * @return DOMDocument
     */
    public function transpileRoot()
    {
        $document = $this->context->getDocument();
        $this->transpile($document);
        return $document;
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


        if ($root && $root->getAttribute('version') === '2.0') {
            $root->setAttribute('version', '1.0');
        }
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
