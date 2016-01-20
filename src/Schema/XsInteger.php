<?php
namespace Genkgo\Xsl\Schema;

/**
 * Class XsInteger
 * @package Genkgo\Xsl\Schema
 */
final class XsInteger extends AbstractXsElement
{
    /**
     * @return string
     */
    protected function getElementName()
    {
        return 'integer';
    }
}
