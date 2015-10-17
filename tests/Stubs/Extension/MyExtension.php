<?php
namespace Genkgo\Xsl\Stubs\Extension;

use Genkgo\Xsl\StringFunction;
use Genkgo\Xsl\Transpiler;
use Genkgo\Xsl\XmlNamespaceInterface;
use Genkgo\Xsl\Xpath\Compiler;

class MyExtension implements XmlNamespaceInterface {

    /**
     * @param Compiler $compiler
     * @return void
     */
    public function registerXpathFunctions(Compiler $compiler)
    {
        $compiler->addNsFunctions([
            new StringFunction('helloWorld', static::class)
        ], 'https://github.com/genkgo/xsl/tree/master/tests/Stubs/Extension/MyExtension');
    }

    /**
     * @param Transpiler $transpiler
     * @param Compiler $compiler
     */
    public function registerTransformers(Transpiler $transpiler, Compiler $compiler)
    {
        ;
    }

    public static function helloWorld (...$args) {
        return 'Hello World was called and received ' . count($args) . ' arguments!';
    }
}