<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Util;

use Genkgo\Xsl\Callback\FunctionInterface;

final class FunctionMap
{
    /**
     * @var array|FunctionInterface[]
     */
    private $functions = [];

    /**
     * @param string $name
     * @param FunctionInterface $function
     * @return FunctionMap
     */
    public function set(string $name, FunctionInterface $function): self
    {
        $this->functions[$name] = $function;
        return $this;
    }

    /**
     * @param string $name
     * @return FunctionInterface
     */
    public function get(string $name): FunctionInterface
    {
        if (isset($this->functions[$name])) {
            return $this->functions[$name];
        }

        throw new \InvalidArgumentException('Cannot find function ' . $name . ' in function map');
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->functions[$name]);
    }
}
