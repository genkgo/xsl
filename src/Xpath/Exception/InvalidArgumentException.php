<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath\Exception;

use Exception;

final class InvalidArgumentException extends Exception
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
