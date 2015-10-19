<?php
namespace Genkgo\Xsl\Xsl;

use Genkgo\Xsl\Callback\ContextFunction;
use Genkgo\Xsl\Callback\ObjectFunction;
use Genkgo\Xsl\Callback\ReturnXsSequenceFunction;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Util\TransformerCollection;
use Genkgo\Xsl\XmlNamespaceInterface;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\Functions\CurrentGroup;
use Genkgo\Xsl\Xsl\Functions\CurrentGroupingKey;
use Genkgo\Xsl\Xsl\Functions\GroupBy;

/**
 * Class XslTransformations
 * @package Genkgo\Xsl\Xsl
 */
class XslTransformations implements XmlNamespaceInterface
{
    /**
     *
     */
    const URI = 'http://www.w3.org/1999/XSL/Transform';

    /**
     * @var Compiler
     */
    private $xpathCompiler;

    /**
     * XslTransformations constructor.
     * @param Compiler $xpathCompiler
     */
    public function __construct(Compiler $xpathCompiler)
    {
        $this->xpathCompiler = $xpathCompiler;
    }

    /**
     * @param TransformerCollection $transformers
     * @param FunctionMap $functions
     * @return void
     */
    public function register(TransformerCollection $transformers, FunctionMap $functions)
    {
        $transformers->attach(new Transformer($this->xpathCompiler));

        $functions->set('format-dateTime', new ObjectFunction('formatDateTime', Functions::class));
        $functions->set('group-by', new GroupBy($this->xpathCompiler), self::URI);

        $functions->setUndashed('formatDate', new ObjectFunction('formatDate', Functions::class));
        $functions->setUndashed('formatTime', new ObjectFunction('formatTime', Functions::class));

        $functions->setUndashed('currentGroupingKey', new CurrentGroupingKey());
        $functions->setUndashed('currentGroup', new CurrentGroup());
    }
}
