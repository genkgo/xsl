<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

use Closure;
use Genkgo\Xsl\TransformationContext;

final class ClosureFunction extends AbstractFunction
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Closure
     */
    private $callback;

    /**
     * @param string $name
     * @param Closure $callback
     */
    public function __construct(string $name, Closure $callback)
    {
        $this->name = $name;
        $this->callback = $callback;
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
        return \call_user_func($this->callback, $arguments, $context);
    }
}
