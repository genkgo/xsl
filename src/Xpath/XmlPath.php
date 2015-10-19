<?php
namespace Genkgo\Xsl\Xpath;

use Genkgo\Xsl\Callback\ObjectFunction;
use Genkgo\Xsl\Callback\ReturnXsScalarFunction;
use Genkgo\Xsl\Callback\ReturnXsSequenceFunction;
use Genkgo\Xsl\Callback\StringFunction;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Util\TransformerCollection;
use Genkgo\Xsl\XmlNamespaceInterface;
use Genkgo\Xsl\Xpath\Functions\Aggregation;
use Genkgo\Xsl\Xpath\Functions\Date;
use Genkgo\Xsl\Xpath\Functions\Math;
use Genkgo\Xsl\Xpath\Functions\Sequence;
use Genkgo\Xsl\Xpath\Functions\Text;

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
            'abs', 'ceiling', 'floor', 'round', 'roundHalfToEven'
        ], $functions, Math::class);

        $this->registerStringFunctions([
            'startsWith', 'endsWith', 'indexOf', 'matches', 'lowerCase',
            'upperCase', 'translate', 'substringAfter', 'substringBefore', 'replace'
        ], $functions, Text::class);

        $this->registerAggregationFunctions(['avg', 'max', 'min'], $functions);

        $functions->set('tokenize', new ReturnXsSequenceFunction(new ObjectFunction('tokenize', Text::class)));
        $functions->set('stringJoin', new ObjectFunction('stringJoin', Text::class));

        $functions->set('remove', new ReturnXsSequenceFunction(new ObjectFunction('remove', Sequence::class)));
        $functions->set('subsequence', new ReturnXsSequenceFunction(new ObjectFunction('subsequence', Sequence::class)));
        $functions->set('reverse', new ReturnXsSequenceFunction(new ObjectFunction('reverse', Sequence::class)));
        $functions->set('insertBefore', new ReturnXsSequenceFunction(new ObjectFunction('insertBefore', Sequence::class)));

        $functions->set('currentTime', new ReturnXsScalarFunction(new ObjectFunction('currentTime', Date::class), 'time'));
        $functions->set('currentDate', new ReturnXsScalarFunction(new ObjectFunction('currentDate', Date::class), 'date'));
        $functions->setRaw('current-dateTime', new ReturnXsScalarFunction(new ObjectFunction('currentDateTime', Date::class), 'dateTime'));
    }

    private function registerStringFunctions(array $list, FunctionMap $functions, $className)
    {
        foreach ($list as $functionName) {
            $functions->set($functionName, new StringFunction($functionName, $className));
        }
    }

    private function registerAggregationFunctions(array $list, FunctionMap $functions)
    {
        foreach ($list as $functionName) {
            $functions->set($functionName, new ObjectFunction($functionName, Aggregation::class));
        }
    }
}
