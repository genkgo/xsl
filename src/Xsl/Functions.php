<?php
namespace Genkgo\Xsl\Xsl;

use Genkgo\Xsl\ObjectFunction;
use Genkgo\Xsl\Xsl\Functions\Formatting;

class Functions
{

    const FLAG_DATE = 0x1;
    const FLAG_TIME = 0x2;

    use Formatting;

    public static function supportedFunctions () {
        return [
            new ObjectFunction('formatDate', static::class),
            new ObjectFunction('formatTime', static::class),
            new ObjectFunction('formatDateTime', static::class, 'format-dateTime'),
        ];
    }

}