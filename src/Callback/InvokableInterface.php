<?php
namespace Genkgo\Xsl\Callback;

/**
 * Interface InvokableInterface
 * @package Genkgo\Xsl\Callback
 */
interface InvokableInterface
{
    /**
     * @param $arguments
     * @return mixed
     */
    public function call($arguments);
}
