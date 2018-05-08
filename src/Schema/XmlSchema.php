<?php
namespace Genkgo\Xsl\Schema;

use Genkgo\Xsl\Callback\ObjectFunction;
use Genkgo\Xsl\Callback\ReturnXsScalarFunction;
use Genkgo\Xsl\Callback\StaticFunction;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Util\TransformerCollection;
use Genkgo\Xsl\XmlNamespaceInterface;

/**
 * Class XmlSchema
 * @package Genkgo\Xsl\Schema
 */
class XmlSchema implements XmlNamespaceInterface
{
    /**
     *
     */
    const URI = 'http://www.w3.org/2001/XMLSchema';

    /**
     * @param TransformerCollection $transformers
     * @param FunctionMap $functions
     * @return void
     */
    public function register(TransformerCollection $transformers, FunctionMap $functions)
    {
        (new StaticFunction(
            self::URI.':date', new ReturnXsScalarFunction(new ObjectFunction([Functions::class, 'xsDate']), 'date'))
        )->register($functions);

        (new StaticFunction(
            self::URI.':time', new ReturnXsScalarFunction(new ObjectFunction([Functions::class, 'xsTime']), 'time'))
        )->register($functions);

        (new StaticFunction(
            self::URI.':dateTime', new ReturnXsScalarFunction(new ObjectFunction([Functions::class, 'xsDateTime']), 'dateTime'))
        )->register($functions);

        (new StaticFunction(
            self::URI.':integer', new ReturnXsScalarFunction(new ObjectFunction([Functions::class, 'xsInteger']), 'integer'))
        )->register($functions);
    }
}
