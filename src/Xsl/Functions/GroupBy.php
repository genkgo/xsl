<?php
namespace Genkgo\Xsl\Xsl\Functions;

use Genkgo\Xsl\Schema\XsSequence;
use Genkgo\Xsl\TransformationContext;

trait GroupBy
{
    public static function currentGroupingKey(TransformationContext $context, $elements)
    {
        return $context->getElementContextFor($elements[0])[0];
    }

    public static function currentGroup(TransformationContext $context, $elements)
    {
        return XsSequence::fromArray($context->getElementContextFor($elements[0])[1]);
    }
}
