<?php
namespace Genkgo\Xsl\Util;

use Genkgo\Xsl\Callback\FunctionInterface;

/**
 * Class FunctionMap
 * @package Genkgo\Xsl\Util
 */
final class FunctionMap
{
    /**
     * @var array|FunctionInterface[]
     */
    private $functions = [];

    /**
     * @param $name
     * @param FunctionInterface $function
     * @return FunctionMap
     */
    public function set($name, FunctionInterface $function)
    {
        $this->functions[$name] = $function;
        return $this;
    }

    /**
     * @param $name
     * @return FunctionInterface|null
     */
    public function get($name)
    {
        if (isset($this->functions[$name])) {
            return $this->functions[$name];
        }

        return null;
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return $this->get($name) !== null;
    }
}
