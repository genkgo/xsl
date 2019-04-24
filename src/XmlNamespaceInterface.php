<?php
declare(strict_types=1);

namespace Genkgo\Xsl;

use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Util\TransformerCollection;

/**
 * Interface XmlNamespaceInterface
 */
interface XmlNamespaceInterface
{
    /**
     * @param TransformerCollection $transformers
     * @param FunctionMap $functions
     * @return void
     */
    public function register(TransformerCollection $transformers, FunctionMap $functions);
}
