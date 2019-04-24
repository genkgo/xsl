<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\Util\FunctionMap;

/**
 * Interface FunctionInterface
 */
interface FunctionInterface
{
    /**
     * @param FunctionMap $functionMap
     * @return void
     */
    public function register(FunctionMap $functionMap);
}
