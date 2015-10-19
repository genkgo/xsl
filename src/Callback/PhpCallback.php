<?php
namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\TransformationContext;

/**
 * Class PhpCallback
 * @package Genkgo\Xsl\Callback
 */
class PhpCallback
{
    /**
     * @var TransformationContext
     */
    private static $context;

    /**
     * @param TransformationContext $context
     */
    public static function set(TransformationContext $context)
    {
        self::$context = $context;
    }

    /**
     *
     */
    public static function reset()
    {
        self::$context = null;
    }

    /**
     * @param $functionName
     * @param ...$arguments
     * @return mixed
     */
    public static function call($functionName, ...$arguments)
    {
        $context = self::$context;
        $function = $context->getFunctions()->get($functionName);

        array_unshift($arguments, $context);
        return call_user_func([$function, 'call'], $arguments);
    }

    /**
     * @param $class
     * @param $method
     * @param ...$arguments
     * @return mixed
     */
    public static function callStatic($class, $method, ...$arguments)
    {
        return call_user_func_array([$class, $method], $arguments);
    }
}
