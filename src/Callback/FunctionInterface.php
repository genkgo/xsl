<?php
namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\Util\FunctionMap;

/**
 * Interface FunctionInterface
 * @package Genkgo\Xsl\Callback
 */
interface FunctionInterface
{
    /**
     * @param FunctionMap $functionMap
     * @return void
     */
    public function register(FunctionMap $functionMap);
}
