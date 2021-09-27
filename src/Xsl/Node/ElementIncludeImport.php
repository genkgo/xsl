<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Node;

use DOMElement;
use Genkgo\Xsl\Util\TransformerCollection;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;

final class ElementIncludeImport implements ElementTransformerInterface
{
    private $transformers;

    /**
     * @param TransformerCollection $transformers
     */
    public function __construct(TransformerCollection $transformers)
    {
        $this->transformers = $transformers;
    }

    /**
     * @param DOMElement $element
     * @return bool
     */
    public function supports(DOMElement $element): bool
    {
        return $element->ownerDocument->baseURI !== null && ($element->localName === 'include' || $element->localName === 'import');
    }

    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element): void
    {
        $href = $element->getAttribute('href');

        $normalizedHref = '/' . \implode(
                '/',
                $this->filterDoubleDots(
                    \array_values(
                        \array_filter(
                            \explode(
                                '/',
                                \dirname($element->ownerDocument->baseURI) . '/' . $href
                            ),
                            function (string $segment) {
                                return $segment !== '.' && $segment !== '';
                            }
                        )
                    )
                )
            );

        static $refs = [];
        if (isset($refs[$normalizedHref])) {
            $element->parentNode->removeChild($element);
            return;
        }

        $refs[$normalizedHref] = true;

        $document = new \DOMDocument();
        $document->substituteEntities = false;
        $document->resolveExternals = false;
        $document->load($normalizedHref);

        if ($document->doctype instanceof \DOMDocumentType && $document->doctype->entities->length > 0) {
            throw new \DOMException('Invalid document, contains entities');
        }

        $root = $document->documentElement;
        if ($root === null) {
            return;
        }

        foreach ($this->transformers as $transformer) {
            $transformer->transform($document);
        }

        if ($root->getAttribute('version') !== '1.0') {
            $root->setAttribute('version', '1.0');
        }

        foreach ($document->documentElement->childNodes as $child) {
            $element->parentNode->insertBefore($element->ownerDocument->importNode($child, true), $element);
        }

        $element->parentNode->removeChild($element);
    }

    private function filterDoubleDots(array $segments)
    {
        $index = 0;
        while (isset($segments[$index])) {
            if ($segments[$index] === '..') {
                unset($segments[$index - 1]);
                unset($segments[$index]);
                $segments = \array_values($segments);
                $index = $index - 2;
            }

            $index++;
        }

        return $segments;
    }
}
