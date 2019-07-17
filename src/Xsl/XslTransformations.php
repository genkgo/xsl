<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl;

use Genkgo\Xsl\Callback\FunctionCollection;
use Genkgo\Xsl\Util\TransformerCollection;
use Genkgo\Xsl\XmlNamespaceInterface;
use Genkgo\Xsl\Xpath\Compiler;

final class XslTransformations implements XmlNamespaceInterface
{
    const URI = 'http://www.w3.org/1999/XSL/Transform';

    /**
     * @var Compiler
     */
    private $xpathCompiler;

    /**
     * @var array
     */
    private $defaultExcludePrefixes;

    /**
     * @param Compiler $xpathCompiler
     * @param array $defaultExcludePrefixes
     */
    public function __construct(
        Compiler $xpathCompiler,
        array $defaultExcludePrefixes = Transformer::DEFAULT_EXCLUDE_PREFIXES
    ) {
        $this->xpathCompiler = $xpathCompiler;
        $this->defaultExcludePrefixes = $defaultExcludePrefixes;
    }

    /**
     * @param TransformerCollection $transformers
     * @param FunctionCollection $functions
     * @return void
     */
    public function register(TransformerCollection $transformers, FunctionCollection $functions): void
    {
        $functions->attach(self::URI, new FunctionMap(self::URI));
        $transformers->attach(
            Transformer::newDefaultTransformer(
                $this->xpathCompiler,
                $this->defaultExcludePrefixes
            )
        );
    }
}
