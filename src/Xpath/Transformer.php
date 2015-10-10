<?php
namespace Genkgo\Xsl\Xpath;

use DOMDocument;
use Genkgo\Xsl\StringFunction;
use Genkgo\Xsl\TransformerInterface;
use Genkgo\Xsl\Transpiler;

/**
 * Class Transformer
 * @package Genkgo\Xsl\Xpath
 */
class Transformer implements TransformerInterface
{
    /**
     * @param DOMDocument $document
     * @param Transpiler $transpiler
     */
    public function transform(DOMDocument $document, Transpiler $transpiler)
    {
        $transpiler->addFunction(new StringFunction('abs', Functions::class));
        $transpiler->addFunction(new StringFunction('ceiling', Functions::class));
        $transpiler->addFunction(new StringFunction('floor', Functions::class));
        $transpiler->addFunction(new StringFunction('round', Functions::class));
        $transpiler->addFunction(new StringFunction('roundHalfToEven', Functions::class));
        $transpiler->addFunction(new StringFunction('endsWith', Functions::class));
        $transpiler->addFunction(new StringFunction('indexOf', Functions::class));
        $transpiler->addFunction(new StringFunction('matches', Functions::class));
        $transpiler->addFunction(new StringFunction('lowerCase', Functions::class));
        $transpiler->addFunction(new StringFunction('upperCase', Functions::class));
        $transpiler->addFunction(new StringFunction('tokenize', Functions::class));
        $transpiler->addFunction(new StringFunction('translate', Functions::class));
        $transpiler->addFunction(new StringFunction('substringAfter', Functions::class));
        $transpiler->addFunction(new StringFunction('substringBefore', Functions::class));
    }
}
