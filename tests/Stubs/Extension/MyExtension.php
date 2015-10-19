<?php
namespace Genkgo\Xsl\Stubs\Extension;

use Genkgo\Xsl\Callback\StringFunction;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Util\TransformerCollection;
use Genkgo\Xsl\XmlNamespaceInterface;

class MyExtension implements XmlNamespaceInterface
{
    const URI = 'https://github.com/genkgo/xsl/tree/master/tests/Stubs/Extension/MyExtension';

    /**
     * @param ...$args
     * @return string
     */
    public static function helloWorld(...$args)
    {
        return 'Hello World was called and received ' . count($args) . ' arguments!';
    }

    /**
     * @param TransformerCollection $transformers
     * @param FunctionMap $functions
     * @return void
     */
    public function register(TransformerCollection $transformers, FunctionMap $functions)
    {
        $functions->set('helloWorld', new StringFunction('helloWorld', static::class), self::URI);
    }
}
