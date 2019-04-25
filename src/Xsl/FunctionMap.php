<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl;

use Genkgo\Xsl\Callback\AbstractLazyFunctionMap;
use Genkgo\Xsl\Xsl\Functions\GroupBy;
use Genkgo\Xsl\Xsl\Functions\GroupIterate;
use Genkgo\Xsl\Xsl\Functions\GroupIterationId;

final class FunctionMap extends AbstractLazyFunctionMap
{
    protected function newStaticFunctionList(): array
    {
        $groupMap = new ForEachGroup\Map();

        return [
            'group-by' => new GroupBy($groupMap),
            'group-iterate' => new GroupIterate($groupMap),
            'group-iteration-id' => new GroupIterationId($groupMap),
        ];
    }
}
