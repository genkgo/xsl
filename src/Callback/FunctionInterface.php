<?php
namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\Util\FunctionMap;

interface FunctionInterface {

    /**
     * @param FunctionMap $functionMap
     * @return void
     */
    public function register (FunctionMap $functionMap);

}