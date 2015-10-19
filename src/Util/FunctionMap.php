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
     * @param null $namespace
     * @return FunctionMap
     */
    public function set($name, FunctionInterface $function, $namespace = null)
    {
        return $this->setRaw($this->dasherize($name), $function, $namespace);
    }

    /**
     * @param $name
     * @param FunctionInterface $function
     * @param null $namespace
     * @return FunctionMap
     */
    public function setRaw($name, FunctionInterface $function, $namespace = null)
    {
        if ($namespace !== null) {
            $name = $namespace . ':' . $name;
        }
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

    /**
     * @param string $methodName
     * @return string
     */
    private static function dasherize($methodName)
    {
        if (ctype_lower($methodName) === false) {
            $methodName = strtolower(preg_replace('/(.)(?=[A-Z])/', '$1'.'-', $methodName));
            $methodName = preg_replace('/\s+/', '', $methodName);
        }

        return $methodName;
    }
}
