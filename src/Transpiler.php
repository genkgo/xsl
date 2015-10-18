<?php
namespace Genkgo\Xsl;

use DOMDocument;

/**
 * Class Transpiler
 * @package Genkgo\Xsl
 */
final class Transpiler
{
    /**
     * @var TransformationContext
     */
    public $context;

    /**
     * @var array|TransformerInterface[]
     */
    private $transformers = [];

    /**
     * @param TransformationContext $context
     */
    public function __construct(TransformationContext $context)
    {
        $this->context = $context;
    }

    /**
     * @param TransformerInterface $transformer
     */
    public function registerTransformer(TransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;
    }

    /**
     * @param DOMDocument $document
     * @return DOMDocument
     */
    public function transpile(DOMDocument $document)
    {
        if ($document->documentElement && $document->documentElement->getAttribute('version') === '2.0') {
            $document->documentElement->setAttribute('version', '1.0');

            foreach ($this->transformers as $transformer) {
                $transformer->transform($document, $this->context);
            }
        }

        return $document;
    }
}
