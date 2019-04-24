<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\TransformationContext;

final class PhpCallback
{
    /**
     * @var TransformationContext|null
     */
    private static $context;

    /**
     * @param TransformationContext $context
     */
    public static function set(TransformationContext $context): void
    {
        self::$context = $context;
    }
    
    public static function reset()
    {
        self::$context = null;
    }

    /**
     * @param string $functionName
     * @param mixed ...$arguments
     * @return mixed
     */
    public static function call(string $functionName, ...$arguments)
    {
        if (self::$context === null) {
            throw new \RuntimeException('No context known when calling function from strylesheet');
        }

        $function = self::$context->getFunctions()->get($functionName);
        $callable = [$function, 'call'];

        if (\is_callable($callable)) {
            return \call_user_func($callable, $arguments, self::$context);
        }

        throw new \RuntimeException('Calling function that is not callable');
    }

    /**
     * @param string $class
     * @param string $method
     * @param mixed ...$arguments
     * @return mixed
     */
    public static function callStatic(string $class, string $method, ...$arguments)
    {
        $callable = [$class, $method];
        if (\is_callable($callable)) {
            return \call_user_func_array($callable, $arguments);
        }

        throw new \RuntimeException('Calling static method that is not callable');
    }
}
