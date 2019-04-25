<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Node;

use DOMElement;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;
use Genkgo\Xsl\Xsl\XslTransformations;

final class ElementAttribute implements ElementTransformerInterface
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
        return (
            $element->ownerDocument->documentElement->getAttribute('version') !== '1.0' &&
            $element->localName === 'attribute' &&
            $element->hasAttribute('select')
        );
    }

    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element): void
    {
        $compiled = $this->xpathCompiler->compile($element->getAttribute('select'), $element);

        $element->removeAttribute('select');

        $valueOf = $element->ownerDocument->createElementNS(XslTransformations::URI, 'xsl:value-of');
        $valueOf->setAttribute('select', $compiled);
        $element->appendChild($valueOf);
    }
}
