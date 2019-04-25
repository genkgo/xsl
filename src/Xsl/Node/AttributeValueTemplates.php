<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Node;

use DOMAttr;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\AttributeTransformerInterface;
use Genkgo\Xsl\Xsl\AttributeValueTemplate;

final class AttributeValueTemplates implements AttributeTransformerInterface
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
     * @param DOMAttr $attribute
     * @return bool
     */
    public function supports(DOMAttr $attribute): bool
    {
        return true;
    }

    /**
     * @param DOMAttr $attribute
     * @credits https://github.com/Saxonica/Saxon-CE/ https://github.com/Saxonica/Saxon-CE/blob/master/notices/MOZILLA.txt
     */
    public function transform(DOMAttr $attribute): void
    {
        (new AttributeValueTemplate($this->xpathCompiler))->expand($attribute);
    }
}
