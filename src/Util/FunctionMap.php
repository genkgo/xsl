<?php
namespace Genkgo\Xsl\Util;

use Genkgo\Xsl\Callback\FunctionInterface;

/**
 * Class FunctionMap
 * @package Genkgo\Xsl\Util
 */
final class FunctionMap {

    /**
     * @var array|FunctionInterface[]
     */
    private $functions = [];

    /**
     * @param $name
     * @param FunctionInterface $function
     * @param null $namespace
     */
    public function set($name, FunctionInterface $function, $namespace = null) {
        if ($namespace !== null) {
            $name = $namespace . ':' . $name;
        }
        $this->functions[$name] = $function;
    }

    public function setUndashed($name, FunctionInterface $function, $namespace = null) {
        $this->set($this->dasherize($name), $function, $namespace);
    }

    /**
     * @param $name
     * @return FunctionInterface|null
     */
    public function get($name) {
        if (isset($this->functions[$name])) {
            return $this->functions[$name];
        }

        return null;
    }

    /**
     * @param $name
     * @return bool
     */
    public function has ($name) {
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