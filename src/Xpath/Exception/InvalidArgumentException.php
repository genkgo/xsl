<?php
namespace Genkgo\Xsl\Xpath\Exception;

use Exception;

class InvalidArgumentException extends Exception {

    private $errorCode;

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param mixed $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }


}