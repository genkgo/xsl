<?php
namespace Genkgo\Xsl\Schema;

use Genkgo\Xsl\Callback\ObjectFunction;
use Genkgo\Xsl\Callback\ReturnXsScalarFunction;
use Genkgo\Xsl\Transpiler;
use Genkgo\Xsl\XmlNamespaceInterface;
use Genkgo\Xsl\Xpath\Compiler;

/**
 * Class XmlSchema
 * @package Genkgo\Xsl\Schema
 */
class XmlSchema implements XmlNamespaceInterface
{
    /**
     *
     */
    const URI = 'http://www.w3.org/2001/XMLSchema';

    /**
     * @param Compiler $compiler
     */
    public function registerXpathFunctions(Compiler $compiler)
    {
        $compiler->addNsFunctions([
            new ReturnXsScalarFunction(new ObjectFunction('xsDate', Functions::class, 'date'), 'date'),
            new ReturnXsScalarFunction(new ObjectFunction('xsTime', Functions::class, 'time'), 'time'),
            new ReturnXsScalarFunction(new ObjectFunction('xsDateTime', Functions::class, 'dateTime'), 'dateTime')
        ], self::URI);
    }

    /**
     * @param Transpiler $transpiler
     * @param Compiler $compiler
     */
    public function registerTransformers(Transpiler $transpiler, Compiler $compiler)
    {
        ;
    }
}
