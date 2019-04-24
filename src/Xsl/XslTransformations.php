<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl;

use Genkgo\Xsl\Callback\ObjectFunction;
use Genkgo\Xsl\Callback\StaticFunction;
use Genkgo\Xsl\Config;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Util\TransformerCollection;
use Genkgo\Xsl\XmlNamespaceInterface;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\Functions\CurrentGroup;
use Genkgo\Xsl\Xsl\Functions\CurrentGroupingKey;
use Genkgo\Xsl\Xsl\Functions\DateFormatting;
use Genkgo\Xsl\Xsl\Functions\GroupBy;
use Genkgo\Xsl\Xsl\Functions\GroupIterate;
use Genkgo\Xsl\Xsl\Functions\GroupIterationId;
use Genkgo\Xsl\Xsl\Node\IncludeWindowsTransformer;

final class XslTransformations implements XmlNamespaceInterface
{
    const URI = 'http://www.w3.org/1999/XSL/Transform';

    /**
     * @var Compiler
     */
    private $xpathCompiler;

    /**
     * @var Config
     */
    private $config;

    /**
     * XslTransformations constructor.
     * @param Compiler $xpathCompiler
     */
    public function __construct(Compiler $xpathCompiler, Config $config)
    {
        $this->xpathCompiler = $xpathCompiler;
        $this->config = $config;
    }

    /**
     * @param TransformerCollection $transformers
     * @param FunctionMap $functions
     * @return void
     */
    public function register(TransformerCollection $transformers, FunctionMap $functions)
    {
        $this->registerGroupFunctions($functions);
        $this->registerFormatDateFunctions($functions);

        $transformers->attach(new Transformer($this->xpathCompiler, $this->config));
    }

    private function registerGroupFunctions(FunctionMap $functions)
    {
        $groupMap = new ForEachGroup\Map();

        (new GroupBy($this->xpathCompiler, $groupMap))->register($functions);
        (new GroupIterate($groupMap))->register($functions);
        (new GroupIterationId($groupMap))->register($functions);
        (new CurrentGroupingKey())->register($functions);
        (new CurrentGroup())->register($functions);
    }

    private function registerFormatDateFunctions(FunctionMap $functions)
    {
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
    }
}
