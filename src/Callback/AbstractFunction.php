<?php
namespace Genkgo\Xsl\Callback;

/**
 * Class AbstractFunction
 * @package Genkgo\Xsl\Callback
 */
abstract class AbstractFunction
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $class;

    /**
     * @param string $name
     * @param string $class
     */
    public function __construct($name, $class)
    {
        $this->name = $name;
        $this->class = $class;
    }

}
