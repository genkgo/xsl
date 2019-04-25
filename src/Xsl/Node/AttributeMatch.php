<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Node;

use DOMElement;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;

final class AttributeMatch implements ElementTransformerInterface
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
        return $element->hasAttribute('match');
    }

    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element): void
    {
        $element->setAttribute(
            'match',
            $this->xpathCompiler->compile(
                $element->getAttribute('match'),
                $element
            )
        );
    }
}
