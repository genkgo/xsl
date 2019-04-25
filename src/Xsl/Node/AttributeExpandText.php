<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Node;

use DOMElement;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;
use Genkgo\Xsl\Xsl\TextValueTemplate;
use Genkgo\Xsl\Xsl\XslTransformations;

final class AttributeExpandText implements ElementTransformerInterface
{
    /**
     * @var Compiler
     */
    private $xpathCompiler;

    /**
     * @param Compiler $compiler
     */
    public function __construct(Compiler $compiler)
    {
        $this->xpathCompiler = $compiler;
    }

    /**
     * @param DOMElement $element
     * @return bool
     */
    public function supports(DOMElement $element): bool
    {
        if ($element->ownerDocument->documentElement->getAttribute('version') !== '3.0') {
            return false;
        }

        if (!$element->hasAttribute('expand-text')) {
            return false;
        }

        if ($element->getAttribute('expand-text') === 'yes') {
            return true;
        }

        return false;
    }

    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element): void
    {
        $valueTemplate = new TextValueTemplate($this->xpathCompiler);

        $childrenQuery = new \DOMXPath($element->ownerDocument);
        $elements = $childrenQuery->query('.//text()', $element);
        foreach ($elements as $element) {
            $overwritesQuery = new \DOMXPath($element->ownerDocument);
            $nearestExpand = $overwritesQuery->evaluate(
                \sprintf(
                    'string(ancestor-or-self::*[namespace-uri() = "%s" and @expand-text][1]/@expand-text)',
                    XslTransformations::URI
                ),
                $element
            );

            if ($nearestExpand === 'yes') {
                $valueTemplate->expand($element);
            }
        }
    }
}
