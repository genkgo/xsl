<?php
declare(strict_types=1);

namespace Genkgo\Xsl;

use Genkgo\Xsl\Callback\FunctionCollection;
use Genkgo\Xsl\Util\TransformerCollection;

interface XmlNamespaceInterface
{
    /**
     * @param TransformerCollection $transformers
     * @param FunctionCollection $functions
     * @return void
     */
    public function register(TransformerCollection $transformers, FunctionCollection $functions): void;
}
