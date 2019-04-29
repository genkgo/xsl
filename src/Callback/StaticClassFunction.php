<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\TransformationContext;

final class StaticClassFunction extends AbstractFunction
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @param string $name
     * @param string $className
     * @param string $methodName
     */
    public function __construct(string $name, string $className, string $methodName)
    {
        $this->name = $name;
        $this->className = $className;
        $this->methodName = $methodName;
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return $this->name;
    }

    /**
     * @param Arguments $arguments
     * @param TransformationContext $context
     * @return mixed
     */
    public function call(Arguments $arguments, TransformationContext $context)
    {
        $callable = [$this->className, $this->methodName];
        if (\is_callable($callable)) {
            return \call_user_func($callable, $arguments);
        }

        throw new \InvalidArgumentException('Argument is not callable');
    }
}
