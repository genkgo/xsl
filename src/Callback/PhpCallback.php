<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\Schema\DataTypeParser;
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
     * @param string $functionQname
     * @param mixed ...$arguments
     * @return mixed
     */
    public static function call(string $functionQname, ...$arguments)
    {
        if (self::$context === null) {
            throw new \RuntimeException('No context known when calling function from strylesheet');
        }

        $dot = \strrpos($functionQname, ':');
        if ($dot !== false) {
            $namespace = \substr($functionQname, 0, $dot);
            $functionName = \substr($functionQname, $dot + 1);

            return self::$context->getFunctions()
                ->get($namespace)
                ->get($functionName)
                ->call(
                    new Arguments(new DataTypeParser(), $arguments),
                    self::$context
                );
        } else {
            return self::$context->getFunctions()
                ->get('')
                ->get($functionQname)
                ->call(
                    new Arguments(new DataTypeParser(), $arguments),
                    self::$context
                );
        }
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
            return \call_user_func(
                $callable,
                new Arguments(new DataTypeParser(), $arguments),
                self::$context
            );
        }

        throw new \RuntimeException('Calling static method that is not callable');
    }
}
