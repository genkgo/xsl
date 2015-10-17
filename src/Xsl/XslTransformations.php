<?php
namespace Genkgo\Xsl\Xsl;

use Genkgo\Xsl\Callback\ContextFunction;
use Genkgo\Xsl\Callback\ObjectFunction;
use Genkgo\Xsl\Callback\ReturnXsFunction;
use Genkgo\Xsl\Transpiler;
use Genkgo\Xsl\XmlNamespaceInterface;
use Genkgo\Xsl\Xpath\Compiler;

/**
 * Class XslTransformations
 * @package Genkgo\Xsl\Xsl
 */
class XslTransformations implements XmlNamespaceInterface {

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
            new ContextFunction('currentGroupingKey', Functions::class),
            new ReturnXsFunction(new ContextFunction('currentGroup', Functions::class), 'sequence')
        ]);
    }

    /**
     * @param Transpiler $transpiler
     * @param Compiler $compiler
     */
    public function registerTransformers(Transpiler $transpiler, Compiler $compiler)
    {
        $transpiler->registerTransformer(new Transformer($compiler));
    }
}