<?php
namespace Genkgo\Xsl;

use DOMDocument;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Util\TransformerCollection;

/**
 * Class Context
 * @package Genkgo\Xsl
 */
class TransformationContext
{
    /**
     * @var DOMDocument
     */
    private $document;
    /**
     * @var array|null
     */
    private $phpFunctions;
    /**
     * @var TransformerCollection
     */
    private $transformers;
    /**
     * @var FunctionMap
     */
    private $functions;

    /**
     * @param DOMDocument $document
     * @param TransformerCollection $transformers
     * @param FunctionMap $functions
     * @param array|null $phpFunctions
     */
    public function __construct(
        DOMDocument $document,
        TransformerCollection $transformers,
        FunctionMap $functions,
        array $phpFunctions = null
    ) {
        $this->document = $document;
        $this->transformers = $transformers;
        $this->functions = $functions;
        $this->phpFunctions = $phpFunctions;
    }

    /**
     * @return DOMDocument
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @return array|null
     */
    public function getPhpFunctions()
    {
        return $this->phpFunctions;
    }

    /**
     * @return FunctionMap
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    /**
     * @return TransformerCollection|TransformerInterface[]
     */
    public function getTransformers()
    {
        return $this->transformers;
    }
}
