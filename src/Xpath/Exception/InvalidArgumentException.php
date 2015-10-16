<?php
namespace Genkgo\Xsl\Xpath\Exception;

use Exception;

/**
 * Class InvalidArgumentException
 * @package Genkgo\Xsl\Xpath\Exception
 */
class InvalidArgumentException extends Exception
{
    /**
     * @var string
     */
    private $errorCode;

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param string $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }
}
