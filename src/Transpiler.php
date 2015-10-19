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
    public function transpileRoot () {
        return $this->transpile($this->context->getDocument());
    }

    /**
     * @param DOMDocument $document
     * @return DOMDocument
     */
    public function transpile(DOMDocument $document)
    {
        if ($document->documentElement && $document->documentElement->getAttribute('version') === '2.0') {
            $document->documentElement->setAttribute('version', '1.0');

            $transformers = $this->context->getTransformers();
            foreach ($transformers as $transformer) {
                $transformer->transform($document, $this->context);
            }
        }

        return $document;
    }

    /**
     * @param Closure $nativeTransformation
     * @return mixed
     */
    public function transform (Closure $nativeTransformation) {
        PhpCallback::attach($this->context);

        $result = $nativeTransformation();

        PhpCallback::detach($this->context);
        return $result;
    }
}
