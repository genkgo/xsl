<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Node;

use DOMElement;
use Genkgo\Xsl\Stream;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;

final class IncludeWindowsTransformer implements ElementTransformerInterface
{
    /**
     * @param DOMElement $element
     * @return bool
     */
    public function supports(DOMElement $element): bool
    {
        $href = $element->getAttribute('href');
        return $element->localName === 'include' && \preg_match('~[A-Z]{1}\:\\\~', $href) === 1;
    }

    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element): void
    {
        $href = $element->getAttribute('href');
        $element->setAttribute('href', Stream::PROTOCOL . Stream::HOST . '/' . \str_replace('\\', '/', $href));
    }
}
