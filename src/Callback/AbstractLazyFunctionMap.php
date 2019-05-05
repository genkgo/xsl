<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

abstract class AbstractLazyFunctionMap implements FunctionMapInterface
{
    /**
     * @var array
     */
    private $functions = [];

    /**
     * @var string
     */
    private $namespace;

    /**
     * @param string $namespace
     */
    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
        $this->functions = $this->newStaticFunctionList();
    }

    /**
     * @return array
     */
    abstract protected function newStaticFunctionList(): array;

    /**
     * @param string $name
     * @return FunctionInterface
     */
    final public function get(string $name): FunctionInterface
    {
        if (!isset($this->functions[$name])) {
            throw new \InvalidArgumentException('Cannot find function ' . $name . ' in function map');
        }

        if ($this->functions[$name] instanceof FunctionInterface === false) {
            [$method, $className] = $this->functions[$name];

            $callable = [$this, $method];
            if (!\is_callable($callable)) {
                throw new \UnexpectedValueException('Cannot create new function for ' . $name);
            }

            $this->functions[$name] = \call_user_func(
                $callable,
                $name,
                $className,
                $this->convertToCamel($name),
                ...\array_slice($this->functions[$name], 2)
            );
        }

        return $this->functions[$name];
    }

    /**
     * @param string $methodName
     * @return string
     */
    private function convertToCamel(string $methodName): string
    {
        $methodName = \ucwords(\str_replace(['-', '_'], ' ', $methodName));
        $methodName = \lcfirst(\str_replace(' ', '', $methodName));
        return $methodName;
    }

    /**
     * @param string $functionName
     * @param string $className
     * @param string $methodName
     * @return FunctionInterface
     */
    final protected function newStringFunction(
        string $functionName,
        string $className,
        string $methodName
    ): FunctionInterface {
        return new StringFunction($this->newFullFunctionQname($functionName), $className, $methodName);
    }

    /**
     * @param string $functionName
     * @param string $className
     * @param string $methodName
     * @return FunctionInterface
     */
    final protected function newStaticClassFunction(
        string $functionName,
        string $className,
        string $methodName
    ): FunctionInterface {
        return new StaticClassFunction($this->newFullFunctionQname($functionName), $className, $methodName);
    }

    /**
     * @param string $functionName
     * @param string $className
     * @param string $methodName
     * @return FunctionInterface
     */
    final protected function newSequenceReturnFunction(
        string $functionName,
        string $className,
        string $methodName
    ): FunctionInterface {
        return new ReturnXsSequenceFunction(
            new StaticClassFunction($this->newFullFunctionQname($functionName), $className, $methodName)
        );
    }

    /**
     * @param string $functionName
     * @param string $className
     * @param string $methodName
     * @param string $returnType
     * @return FunctionInterface
     */
    final protected function newScalarReturnFunction(
        string $functionName,
        string $className,
        string $methodName,
        string $returnType
    ): FunctionInterface {
        return new ReturnXsScalarFunction(
            new StaticClassFunction($this->newFullFunctionQname($functionName), $className, $methodName),
            $returnType
        );
    }

    /**
     * @param string $functionName
     * @return string
     */
    protected function newFullFunctionQname(string $functionName): string
    {
        if ($this->namespace === '') {
            return $functionName;
        }

        return $this->namespace . ':' . $functionName;
    }
}
