<?php
namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\TransformationContext;

/**
 * Class PhpCallback
 * @package Genkgo\Xsl\Callback
 */
class PhpCallback {

    /**
     * @var TransformationContext[]
     */
    private static $context = [];

    /**
     * @param TransformationContext $context
     */
    public static function attach (TransformationContext $context) {
        self::$context[spl_object_hash($context)] = $context;
    }

    /**
     * @param TransformationContext $context
     */
    public static function detach (TransformationContext $context) {
        unset(self::$context[spl_object_hash($context)]);
    }

    /**
     * @param $class
     * @param $method
     * @param ...$arguments
     * @return mixed
     */
    public static function call ($class, $method, ...$arguments) {
        return call_user_func_array([$class, $method], $arguments);
    }

    /**
     * @param $objectHash
     * @param $class
     * @param $method
     * @param ...$arguments
     * @return mixed
     */
    public static function callContext ($objectHash, $class, $method, ...$arguments) {
        array_unshift($arguments, self::$context[$objectHash]);

        return call_user_func_array([$class, $method], $arguments);
    }

}