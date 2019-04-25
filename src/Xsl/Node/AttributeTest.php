<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Node;

use DOMElement;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;

final class AttributeTest implements ElementTransformerInterface
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
        return $element->hasAttribute('test');
    }

    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element): void
    {
        $element->setAttribute(
            'test',
            $this->xpathCompiler->compile(
                $element->getAttribute('test'),
                $element
            )
        );
    }
}
