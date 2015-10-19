<?php
namespace Genkgo\Xsl\Schema;

use Genkgo\Xsl\Callback\ObjectFunction;
use Genkgo\Xsl\Callback\ReturnXsScalarFunction;
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
        $functions->set('date', new ReturnXsScalarFunction(new ObjectFunction('xsDate', Functions::class), 'date'), self::URI);
        $functions->set('time', new ReturnXsScalarFunction(new ObjectFunction('xsTime', Functions::class), 'time'), self::URI);
        $functions->setRaw('dateTime', new ReturnXsScalarFunction(new ObjectFunction('xsDateTime', Functions::class), 'dateTime'), self::URI);
    }
}
