<?php
namespace Genkgo\Xsl;

use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Util\TransformerCollection;

/**
 * Interface XmlNamespaceInterface
 * @package Genkgo\Xsl
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
