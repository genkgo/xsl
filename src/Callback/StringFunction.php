<?php declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use Genkgo\Xsl\TransformationContext;

final class StringFunction extends AbstractFunction
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
            $arguments = \array_map(
                function ($expectString) {
                    if (\is_array($expectString) && isset($expectString[0]) && isset($expectString[0]->textContent)) {
                        return $expectString[0]->textContent;
                    }

                    return $expectString;
                },
                $arguments->unpackFromSchemaType()
            );

            return \call_user_func_array($callable, $arguments);
        }

        throw new \InvalidArgumentException('Argument is not callable');
    }
}
