<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Schema;

final class XsInteger extends AbstractXsElement
{
    /**
     * @return string
     */
    protected function getElementName(): string
    {
        return 'integer';
    }

    /**
     * @param \DOMNode $node
     * @return int
     */
    public static function parseNode(\DOMNode $node): int
    {
        return (int) $node->textContent;
    }
}
