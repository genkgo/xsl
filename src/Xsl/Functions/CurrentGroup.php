<?php
namespace Genkgo\Xsl\Xsl\Functions;

use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\Callback\ReplaceFunctionInterface;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xpath\Lexer;
use Genkgo\Xsl\Xsl\Functions;

/**
 * Class CurrentGroupingKey
 * @package Genkgo\Xsl\Xsl\Functions
 */
class CurrentGroup implements ReplaceFunctionInterface, FunctionInterface
{
    /**
     * @param FunctionMap $functionMap
     */
    public function register(FunctionMap $functionMap)
    {
        $functionMap->set('current-group', $this);
    }

    /**
     * @param Lexer $lexer
     * @return array|string[]
     */
    public function replace(Lexer $lexer)
    {
        $groupId = substr($lexer->peek($lexer->key() + 2), 1, -1);

        $resultTokens = [];
        $resultTokens[] = '$current-un-grouped-' . $groupId;
        $resultTokens[] = '[';
        $resultTokens[] = 'generate-id';
        $resultTokens[] = '(';
        $resultTokens[] = '.';
        $resultTokens[] = ')';
        $resultTokens[] = '=';
        $resultTokens[] = '$current-group-' . $groupId;
        $resultTokens[] = '//';
        $resultTokens[] = 'xsl:element-id';
        $resultTokens[] = ']';

        $lexer->seek($lexer->key() + 3);
        return $resultTokens;
    }
}
