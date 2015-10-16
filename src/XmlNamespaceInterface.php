<?php
namespace Genkgo\Xsl;

use Genkgo\Xsl\Xpath\Compiler;

/**
 * Interface XmlNamespaceInterface
 * @package Genkgo\Xsl
 */
interface XmlNamespaceInterface {

    /**
     * @param Compiler $compiler
     * @return void
     */
    public function registerXpathFunctions (Compiler $compiler);

}