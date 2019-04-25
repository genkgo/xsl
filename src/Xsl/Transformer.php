<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl;

use DOMAttr;
use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Genkgo\Xsl\Schema\XmlSchema;
use Genkgo\Xsl\TransformerInterface;
use Genkgo\Xsl\Util\FetchNamespacesFromNode;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\Node\AttributeExpandText;
use Genkgo\Xsl\Xsl\Node\AttributeValueTemplates;
use Genkgo\Xsl\Xsl\Node\AttributeMatch;
use Genkgo\Xsl\Xsl\Node\AttributeSelect;
use Genkgo\Xsl\Xsl\Node\AttributeTest;
use Genkgo\Xsl\Xsl\Node\ElementAttribute;
use Genkgo\Xsl\Xsl\Node\ElementForEachGroup;
use Genkgo\Xsl\Xsl\Node\ElementValueOf;
use Genkgo\Xsl\Xsl\Node\IncludeWindowsTransformer;

final class Transformer implements TransformerInterface
{
    private const DEFAULT_EXCLUDE_PREFIXES = ['php', 'xs'];

    /**
     * @var ElementTransformerInterface[]
     */
    private $elementTransformers = [];

    /**
     * @var AttributeTransformerInterface[]
     */
    private $attributeTransformers = [];

    /**
     * @param Compiler $xpathCompiler
     */
    public function __construct(Compiler $xpathCompiler)
    {
        $this->elementTransformers = [
            new ElementForEachGroup($xpathCompiler),
            new AttributeExpandText($xpathCompiler),
            new AttributeMatch($xpathCompiler),
            new AttributeSelect($xpathCompiler),
            new AttributeTest($xpathCompiler),
            new ElementValueOf(),
            new ElementAttribute(),
        ];

        // @codeCoverageIgnoreStart
        if (PHP_OS === 'WINNT') {
            $this->elementTransformers[] = new IncludeWindowsTransformer();
        }
        // @codeCoverageIgnoreEnd

        $this->attributeTransformers = [
            new AttributeValueTemplates($xpathCompiler)
        ];
    }

    /**
     * @param DOMDocument $document
     */
    public function transform(DOMDocument $document): void
    {
        $root = $document->documentElement;

        $namespaces = FetchNamespacesFromNode::fetch($document->documentElement);
        $xslPrefix = \array_search(XslTransformations::URI, $namespaces);

        $documentPrefixes = \preg_split('/\s/', (string)$root->getAttribute('exclude-result-prefixes'));
        if ($documentPrefixes === false) {
            $documentPrefixes = [];
        }

        $excludePrefixes = \array_merge($documentPrefixes, self::DEFAULT_EXCLUDE_PREFIXES);

        if (\in_array('#all', $excludePrefixes) === true) {
            $excludePrefixes = \array_merge($excludePrefixes, \array_keys($namespaces));
            $excludePrefixes = \array_filter(
                $excludePrefixes,
                function ($prefix) {
                    return $prefix !== '#all';
                }
            );
        }

        $excludePrefixes = \array_unique($excludePrefixes);

        $root->setAttribute('xmlns:php', 'http://php.net/xsl');
        $root->setAttribute('xmlns:xs', XmlSchema::URI);
        $root->setAttribute('exclude-result-prefixes', \implode(' ', $excludePrefixes));

        $this->transformElements($document, (string)$xslPrefix);
        $this->transformAttributes($document, (string)$xslPrefix);
    }

    /**
     * @param DOMDocument $document
     * @param string $xslPrefix
     */
    private function transformElements(DOMDocument $document, string $xslPrefix) : void
    {
        /** @var DOMNodeList|DOMElement[] $list */
        $matchAndSelectElements = new DOMXPath($document);
        $list = $matchAndSelectElements->query('//' . $xslPrefix . ':*');
        foreach ($list as $element) {
            foreach ($this->elementTransformers as $elementTransformer) {
                if ($elementTransformer->supports($element)) {
                    $elementTransformer->transform($element);
                }
            }
        }
    }

    /**
     * @param DOMDocument $document
     * @param string $xslPrefix
     */
    private function transformAttributes(DOMDocument $document, $xslPrefix)
    {
        /** @var DOMNodeList|DOMAttr[] $list */
        $matchAndSelectElements = new DOMXPath($document);
        $lengthPrefix = \strlen($xslPrefix);
        $expression = '//*[substring(name(), 1, ' . $lengthPrefix . ') != "' . $xslPrefix . '"]/@*[contains(., "{") or contains(., "}")]';

        $list = $matchAndSelectElements->query($expression);
        foreach ($list as $attribute) {
            foreach ($this->attributeTransformers as $attributeTransformer) {
                if ($attributeTransformer->supports($attribute)) {
                    $attributeTransformer->transform($attribute);
                }
            }
        }
    }
}
