<?php
namespace Genkgo\Xsl\Xsl;

use Genkgo\Xsl\ObjectFunction;
use Genkgo\Xsl\XmlNamespaceInterface;
use Genkgo\Xsl\Xpath\Compiler;

/**
 * Class XslTransformations
 * @package Genkgo\Xsl\Xsl
 */
class XslTransformations implements XmlNamespaceInterface{

    /**
     *
     */
    const URI = 'http://www.w3.org/1999/XSL/Transform';

    /**
     * @param Compiler $compiler
     */
    public function registerXpathFunctions (Compiler $compiler) {
        $compiler->addFunctions([
            new ObjectFunction('formatDate', Functions::class),
            new ObjectFunction('formatTime', Functions::class),
            new ObjectFunction('formatDateTime', Functions::class, 'format-dateTime'),
        ]);
    }

}