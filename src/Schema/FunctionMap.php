<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Schema;

use Genkgo\Xsl\Callback\AbstractLazyFunctionMap;

final class FunctionMap extends AbstractLazyFunctionMap
{
    protected function newStaticFunctionList(): array
    {
        return [
            'date' => ['newScalarReturnFunction', Functions::class, 'date'],
            'time' => ['newScalarReturnFunction', Functions::class, 'time'],
            'dateTime' => ['newScalarReturnFunction', Functions::class, 'dateTime'],
            'dayTimeDuration' => ['newScalarReturnFunction', Functions::class, 'dayTimeDuration'],
            'integer' => ['newScalarReturnFunction', Functions::class, 'integer'],
        ];
    }
}
