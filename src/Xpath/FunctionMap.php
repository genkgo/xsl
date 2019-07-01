<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath;

use Closure;
use Genkgo\Xsl\Callback\AbstractLazyFunctionMap;
use Genkgo\Xsl\Callback\ClosureFunction;
use Genkgo\Xsl\Xpath\Functions\Aggregation;
use Genkgo\Xsl\Xpath\Functions\Date;
use Genkgo\Xsl\Xpath\Functions\Math;
use Genkgo\Xsl\Xpath\Functions\Sequence;
use Genkgo\Xsl\Xpath\Functions\Text;
use Genkgo\Xsl\Xsl\Functions\CurrentGroup;
use Genkgo\Xsl\Xsl\Functions\CurrentGroupingKey;
use Genkgo\Xsl\Xsl\Functions\DateFormatting;
use Genkgo\Xsl\Xsl\Functions\Formatter\IntlDateTimeFormatter;

final class FunctionMap extends AbstractLazyFunctionMap
{
    protected function newStaticFunctionList(): array
    {
        $formatting = new DateFormatting(
            new IntlDateTimeFormatter(
                \date_default_timezone_get()
            )
        );

        return [
            'abs' => ['newStringFunction', Math::class],
            'ceiling' => ['newStringFunction', Math::class],
            'floor' => ['newStringFunction', Math::class],
            'round' => ['newStringFunction', Math::class],
            'round-half-to-even' => ['newStringFunction', Math::class],
            'starts-with' => ['newStringFunction', Text::class],
            'ends-with' => ['newStringFunction', Text::class],
            'matches' => ['newStringFunction', Text::class],
            'compare' => ['newStringFunction', Text::class],
            'lower-case' => ['newStringFunction', Text::class],
            'upper-case' => ['newStringFunction', Text::class],
            'translate' => ['newStringFunction', Text::class],
            'substring-after' => ['newStringFunction', Text::class],
            'substring-before' => ['newStringFunction', Text::class],
            'replace' => ['newStringFunction', Text::class],
            'encode-for-uri' => ['newStringFunction', Text::class],
            'avg' => ['newStaticClassFunction', Aggregation::class],
            'max' => ['newStaticClassFunction', Aggregation::class],
            'min' => ['newStaticClassFunction', Aggregation::class],
            'string-join' => ['newStaticClassFunction', Text::class],
            'tokenize' => ['newSequenceReturnFunction', Text::class],
            'codepoints-to-string' => ['newStaticClassFunction', Text::class],
            'string-to-codepoints' => ['newSequenceReturnFunction', Text::class],
            'in-scope-prefixes' => ['newSequenceReturnFunction', Text::class],
            'index-of' => ['newStaticClassFunction', Sequence::class],
            'remove' => ['newSequenceReturnFunction', Sequence::class],
            'subsequence' => ['newSequenceReturnFunction', Sequence::class],
            'reverse' => ['newSequenceReturnFunction', Sequence::class],
            'unordered' => ['newSequenceReturnFunction', Sequence::class],
            'insert-before' => ['newSequenceReturnFunction', Sequence::class],
            'distinct-values' => ['newSequenceReturnFunction', Sequence::class],
            'current-time' => ['newScalarReturnFunction', Date::class, 'time'],
            'current-date' => ['newScalarReturnFunction', Date::class, 'date'],
            'current-dateTime' => ['newScalarReturnFunction', Date::class, 'dateTime'],
            'year-from-date' => ['newScalarReturnFunction', Date::class, 'integer'],
            'year-from-dateTime' => ['newScalarReturnFunction', Date::class, 'integer'],
            'month-from-date' => ['newScalarReturnFunction', Date::class, 'integer'],
            'month-from-dateTime' => ['newScalarReturnFunction', Date::class, 'integer'],
            'day-from-date' => ['newScalarReturnFunction', Date::class, 'integer'],
            'day-from-dateTime' => ['newScalarReturnFunction', Date::class, 'integer'],
            'hours-from-time' => ['newScalarReturnFunction', Date::class, 'integer'],
            'hours-from-dateTime' => ['newScalarReturnFunction', Date::class, 'integer'],
            'minutes-from-time' => ['newScalarReturnFunction', Date::class, 'integer'],
            'minutes-from-dateTime' => ['newScalarReturnFunction', Date::class, 'integer'],
            'seconds-from-time' => ['newScalarReturnFunction', Date::class, 'integer'],
            'seconds-from-dateTime' => ['newScalarReturnFunction', Date::class, 'integer'],
            'years-from-duration' => ['newScalarReturnFunction', Date::class, 'integer'],
            'months-from-duration' => ['newScalarReturnFunction', Date::class, 'integer'],
            'days-from-duration' => ['newScalarReturnFunction', Date::class, 'integer'],
            'hours-from-duration' => ['newScalarReturnFunction', Date::class, 'integer'],
            'minutes-from-duration' => ['newScalarReturnFunction', Date::class, 'integer'],
            'seconds-from-duration' => ['newScalarReturnFunction', Date::class, 'integer'],
            'current-grouping-key' => new CurrentGroupingKey(),
            'current-group' => new CurrentGroup(),
            'format-dateTime' => new ClosureFunction(
                'format-dateTime',
                Closure::fromCallable([$formatting, 'formatDateTime'])
            ),
            'format-date' => new ClosureFunction(
                'format-date',
                Closure::fromCallable([$formatting, 'formatDate'])
            ),
            'format-time' => new ClosureFunction(
                'format-time',
                Closure::fromCallable([$formatting, 'formatTime'])
            ),
        ];
    }
}
