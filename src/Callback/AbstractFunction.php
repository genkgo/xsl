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
     * @var string
     */
    protected $xpathMethod;

    /**
     * @param string $name
     * @param string $class
     * @param null|string $xpathMethod
     */
    public function __construct($name, $class, $xpathMethod = null)
    {
        $this->name = $name;
        $this->class = $class;

        if ($xpathMethod === null) {
            $xpathMethod = $this->dasherize($name);
        }

        $this->xpathMethod = $xpathMethod;
    }

    /**
     * @return string
     */
    public function getXpathMethod()
    {
        return $this->xpathMethod;
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
