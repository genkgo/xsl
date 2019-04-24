<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\TransformationContext;

interface MethodCallInterface
{
    /**
     * @param array $arguments
     * @param TransformationContext $context
     * @return mixed
     */
    public function call(array $arguments, TransformationContext $context);
}
