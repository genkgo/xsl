<?php
namespace Genkgo\Xsl\Xsl;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Genkgo\Xsl\DocumentContext;
use Genkgo\Xsl\Schema\XmlSchema;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\TransformerInterface;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\Node\AttributeMatch;
use Genkgo\Xsl\Xsl\Node\AttributeSelect;
use Genkgo\Xsl\Xsl\Node\ElementForEachGroup;
use Genkgo\Xsl\Xsl\Node\ElementIteration;
use Genkgo\Xsl\Xsl\Node\ElementValueOf;

/**
 * Class Transformer
 * @package Genkgo\Xsl\Xsl
 */
class Transformer implements TransformerInterface
{
    /**
     * @var ElementTransformerInterface[]
     */
    private $elementTransformers = [];

    /**
     * Transformer constructor.
     * @param Compiler $xpathCompiler
     */
    public function __construct(Compiler $xpathCompiler)
    {
        $this->elementTransformers = [
            new AttributeMatch($xpathCompiler),
            new AttributeSelect($xpathCompiler),
            new ElementValueOf(),
            new ElementForEachGroup(),
            new ElementIteration(),
        ];
    }


    /**
     * @param DOMDocument $document
     * @param TransformationContext $transformationContext
     */
    public function transform(DOMDocument $document, TransformationContext $transformationContext)
    {
        $documentContext = new DocumentContext($transformationContext);
        $documentContext->setNamespaces($this->retrieveNamespacesFromDocument($document));

        $document->documentElement->setAttribute('xmlns:php', 'http://php.net/xsl');
        $document->documentElement->setAttribute('xmlns:xs', XmlSchema::URI);

        $matchAndSelectElements = new DOMXPath($document);

        /** @var DOMNodeList|DOMElement[] $list */
        $list = $matchAndSelectElements->query('//xsl:*[@match|@select]');
        foreach ($list as $element) {
            foreach ($this->elementTransformers as $elementTransformer) {
                $elementTransformer->transform($element, $documentContext);
            }
        }
    }

    /**
     * @param DOMDocument $document
     * @return array
     */
    public static function retrieveNamespacesFromDocument(DOMDocument $document)
    {
        $namespaces = [];

        $listOfNamespaces = new DOMXPath($document);
        foreach ($listOfNamespaces->query('namespace::*') as $node) {
            $namespaces[$node->localName] = $node->namespaceURI;
        }

        return $namespaces;
    }
}
