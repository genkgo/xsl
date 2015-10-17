<?php
namespace Genkgo\Xsl;

use Genkgo\Xsl\Exception\UnknownNamespaceException;

/**
 * Class DocumentContext
 * @package Genkgo\Xsl
 */
class DocumentContext {

    /**
     * @var TransformationContext
     */
    private $transformationContext;
    /**
     * @var
     */
    private $namespaces;

    /**
     * @param TransformationContext $context
     */
    public function __construct (TransformationContext $context)
    {
        $this->transformationContext = $context;
    }

    /**
     * @return TransformationContext
     */
    public function getTransformationContext()
    {
        return $this->transformationContext;
    }

    /**
     * @param $namespaces|string[]
     */
    public function setNamespaces($namespaces)
    {
        $this->namespaces = $namespaces;
    }

    /**
     * @param $localName
     * @return string
     * @throws UnknownNamespaceException
     */
    public function getNamespace($localName)
    {
        if (isset($this->namespaces[$localName])) {
            return $this->namespaces[$localName];
        }

        throw new UnknownNamespaceException();
    }

}