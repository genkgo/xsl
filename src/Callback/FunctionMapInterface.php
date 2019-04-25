<?php declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

interface FunctionMapInterface
{
    /**
     * @param string $name
     * @return FunctionInterface
     */
    public function get(string $name): FunctionInterface;
}
