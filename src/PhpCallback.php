<?php
namespace Genkgo\Xsl;

class PhpCallback {

    /**
     * @var Transpiler[]
     */
    private static $contexts = [];

    public static function attach (Transpiler $transpiler) {
        self::$contexts[spl_object_hash($transpiler)] = $transpiler;
    }

    public static function detach (Transpiler $transpiler) {
        unset(self::$contexts[spl_object_hash($transpiler)]);
    }

    public static function call ($class, $method, ...$arguments) {
        return call_user_func_array([$class, $method], $arguments);
    }

}