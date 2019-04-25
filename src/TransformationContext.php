<?php
declare(strict_types=1);

namespace Genkgo\Xsl;

use DOMDocument;
use Genkgo\Xsl\Callback\FunctionCollection;
use Genkgo\Xsl\Util\TransformerCollection;

final class TransformationContext
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
     * @var FunctionCollection
     */
    private $functions;

    /**
     * @param DOMDocument $document
     * @param TransformerCollection $transformers
     * @param FunctionCollection $functions
     * @param array|null $phpFunctions
     */
    public function __construct(
        DOMDocument $document,
        TransformerCollection $transformers,
        FunctionCollection $functions,
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
     * @return FunctionCollection
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
