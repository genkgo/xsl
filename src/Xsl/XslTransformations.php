<?php
namespace Genkgo\Xsl\Xsl;

use Genkgo\Xsl\Callback\ObjectFunction;
use Genkgo\Xsl\Callback\StaticFunction;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Util\TransformerCollection;
use Genkgo\Xsl\XmlNamespaceInterface;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\Functions\CurrentGroup;
use Genkgo\Xsl\Xsl\Functions\CurrentGroupingKey;
use Genkgo\Xsl\Xsl\Functions\DateFormatting;
use Genkgo\Xsl\Xsl\Functions\GroupBy;
use Genkgo\Xsl\Xsl\Functions\GroupIterate;

/**
 * Class XslTransformations
 * @package Genkgo\Xsl\Xsl
 */
final class XslTransformations implements XmlNamespaceInterface
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

        (new StaticFunction(
            'format-dateTime',
            new ObjectFunction([DateFormatting::class, 'formatDateTime'])
        ))->register($functions);
        (new StaticFunction(
            'format-date',
            new ObjectFunction([DateFormatting::class, 'formatDate'])
        ))->register($functions);
        (new StaticFunction(
            'format-time',
            new ObjectFunction([DateFormatting::class, 'formatTime'])
        ))->register($functions);

        $groupMap = new ForEachGroup\Map();
        (new GroupBy($this->xpathCompiler, $groupMap))->register($functions);
        (new GroupIterate($groupMap))->register($functions);
        (new CurrentGroupingKey())->register($functions);
        (new CurrentGroup())->register($functions);
    }
}
