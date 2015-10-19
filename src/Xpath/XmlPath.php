<?php
namespace Genkgo\Xsl\Xpath;

use Genkgo\Xsl\Callback\ObjectFunction;
use Genkgo\Xsl\Callback\ReturnXsScalarFunction;
use Genkgo\Xsl\Callback\ReturnXsSequenceFunction;
use Genkgo\Xsl\Callback\StringFunction;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Util\TransformerCollection;
use Genkgo\Xsl\XmlNamespaceInterface;

class XmlPath implements XmlNamespaceInterface
{
    const URI = '';

    /**
     * @param TransformerCollection $transformers
     * @param FunctionMap $functions
     * @return void
     */
    public function register(TransformerCollection $transformers, FunctionMap $functions)
    {
        $this->registerStringFunctions([
            'abs', 'ceiling', 'floor', 'round', 'roundHalfToEven', 'startsWith', 'endsWith', 'indexOf', 'matches', 'lowerCase',
            'upperCase', 'translate', 'substringAfter', 'substringBefore', 'replace'
        ], $functions);

        $this->registerObjectFunctions(['stringJoin', 'avg', 'max', 'min'], $functions);

        $functions->set('tokenize', new ReturnXsSequenceFunction(new ObjectFunction('tokenize', Functions::class)));
        $functions->set('remove', new ReturnXsSequenceFunction(new ObjectFunction('remove', Functions::class)));
        $functions->set('subsequence', new ReturnXsSequenceFunction(new ObjectFunction('subsequence', Functions::class)));
        $functions->set('reverse', new ReturnXsSequenceFunction(new ObjectFunction('reverse', Functions::class)));

        $functions->setUndashed('insertBefore', new ReturnXsSequenceFunction(new ObjectFunction('insertBefore', Functions::class)));

        $functions->setUndashed(
            'currentTime',
            new ReturnXsScalarFunction(new ObjectFunction('currentTime', Functions::class), 'time')
        );
        $functions->setUndashed(
            'currentDate',
            new ReturnXsScalarFunction(new ObjectFunction('currentDate', Functions::class), 'date')
        );
        $functions->set(
            'current-dateTime',
            new ReturnXsScalarFunction(new ObjectFunction('currentDateTime', Functions::class), 'dateTime')
        );
    }

    private function registerStringFunctions(array $list, FunctionMap $functions)
    {
        foreach ($list as $functionName) {
            $functions->setUndashed($functionName, new StringFunction($functionName, Functions::class));
        }
    }

    private function registerObjectFunctions(array $list, FunctionMap $functions)
    {
        foreach ($list as $functionName) {
            $functions->setUndashed($functionName, new ObjectFunction($functionName, Functions::class));
        }
    }
}
