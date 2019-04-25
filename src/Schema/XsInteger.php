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
}
