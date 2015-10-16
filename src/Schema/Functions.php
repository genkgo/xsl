<?php
namespace Genkgo\Xsl\Schema;

use DateTimeImmutable;
use Genkgo\Xsl\ObjectFunction;

class Functions {

    public static function supportedFunctions () {
        return [
            new ObjectFunction('xsDate', static::class, 'date'),
            new ObjectFunction('xsTime', static::class, 'time'),
            new ObjectFunction('xsDateTime', static::class, 'dateTime')
        ];
    }

    public static function xsDate ($value) {
        return new XsDate(DateTimeImmutable::createFromFormat(XsDate::FORMAT, $value));
    }

    public static function xsTime ($value) {
        return new XsTime(DateTimeImmutable::createFromFormat(XsTime::FORMAT, $value));
    }

    public static function xsDateTime ($value) {
        return new XsDateTime(DateTimeImmutable::createFromFormat(XsDateTime::FORMAT, $value));
    }

}