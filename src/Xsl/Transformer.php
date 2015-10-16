<?php
namespace Genkgo\Xsl\Xsl;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Genkgo\Xsl\Context;
use Genkgo\Xsl\TransformerInterface;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\Node\AttributeMatch;
use Genkgo\Xsl\Xsl\Node\AttributeSelect;
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
        ];
    }


    /**
     * @param DOMDocument $document
     * @param Context $documentContext
     */
    public function transform(DOMDocument $document, Context $documentContext)
    {
        $localContext = new Context($documentContext->getDocument());
        $localContext->setNamespaces($this->retrieveNamespacesFromDocument($document));

        $document->documentElement->setAttribute('xmlns:php', 'http://php.net/xsl');
        $matchAndSelectElements = new DOMXPath($document);

        /** @var DOMNodeList|DOMElement[] $list */
        $list = $matchAndSelectElements->query('//xsl:*[@match|@select]');
        foreach ($list as $element) {
            foreach ($this->elementTransformers as $elementTransformer) {
                $elementTransformer->transform($element, $localContext);
            }
        }
    }

    /**
     * @param DOMDocument $document
     * @return array
     */
    private function retrieveNamespacesFromDocument(DOMDocument $document)
    {
        $namespaces = [];

        $listOfNamespaces = new DOMXPath($document);
        foreach ($listOfNamespaces->query('namespace::*') as $node) {
            $namespaces[$node->localName] = $node->namespaceURI;
        }

        return $namespaces;
    }
}
