<?php
namespace Genkgo\Xsl\Xsl;

use Genkgo\Xsl\ObjectFunction;
use Genkgo\Xsl\Xsl\Functions\DateFormatter;

/**
 * Class Functions
 * @package Genkgo\Xsl\Xsl
 */
class Functions
{
    /**
     *
     */
    const FLAG_DATE = 0x1;
    /**
     *
     */
    const FLAG_TIME = 0x2;

    use DateFormatter;

}
