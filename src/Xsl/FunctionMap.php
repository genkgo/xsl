<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl;

use Genkgo\Xsl\Callback\AbstractLazyFunctionMap;

final class FunctionMap extends AbstractLazyFunctionMap
{
    protected function newStaticFunctionList(): array
    {
        return [];
    }
}
