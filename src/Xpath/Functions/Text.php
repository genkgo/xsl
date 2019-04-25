<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath\Functions;

use DOMElement;
use Genkgo\Xsl\Callback\Arguments;
use Genkgo\Xsl\Exception\CastException;
use Genkgo\Xsl\Schema\XsSequence;
use Genkgo\Xsl\Util\Assert;
use Genkgo\Xsl\Util\FetchNamespacesFromNode;

final class Text
{
    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     *
     * @author Salman A
     * @link http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
     */
    public static function startsWith(string $haystack, string $needle): bool
    {
        return $needle === "" || \strrpos($haystack, $needle, -\strlen($haystack)) !== false;
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     *
     * @author Salman A
     * @link http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
     */
    public static function endsWith(string $haystack, string $needle): bool
    {
        return $needle === "" || (($temp = \strlen($haystack) - \strlen($needle)) >= 0 && \strpos($haystack, $needle, $temp) !== false);
    }

    /**
     * @param string $input
     * @param string $pattern
     * @param string $flags
     * @return bool
     */
    public static function matches(string $input, string $pattern, $flags = ''): bool
    {
        return \preg_match('/'.$pattern.'/'.$flags, $input) === 1;
    }

    /**
     * @param string $input
     * @return string
     */
    public static function lowerCase(string $input): string
    {
        return \strtolower($input);
    }

    /**
     * @param string $input
     * @return string
     */
    public static function upperCase(string $input): string
    {
        return \strtoupper($input);
    }

    /**
     * @param string $input
     * @param string $mapString
     * @param string $translateString
     * @return string
     */
    public static function translate(string $input, string $mapString, string $translateString): string
    {
        return \strtr($input, $mapString, $translateString);
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @return string
     */
    public static function substringAfter(string $haystack, string $needle): string
    {
        if ($needle === '') {
            return $haystack;
        }

        $position = \strpos($haystack, $needle);
        if ($position === false) {
            return '';
        }

        return \substr($haystack, $position + \strlen($needle));
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @return string
     */
    public static function substringBefore(string $haystack, string $needle): string
    {
        if ($needle === '') {
            return $haystack;
        }

        $position = \strpos($haystack, $needle);
        if ($position === false) {
            return '';
        }

        return \substr($haystack, 0, $position);
    }

    /**
     * @param string $input
     * @param string $pattern
     * @param string $flags
     * @return XsSequence
     */
    public static function tokenize(Arguments $arguments): XsSequence
    {
        $input = $arguments->castAsScalar(0);
        $pattern = $arguments->castAsScalar(1);

        try {
            $flags = $arguments->castAsScalar(2);
        } catch (\InvalidArgumentException $e) {
            $flags = '';
        }

        $split = \preg_split('/'.$pattern.'/'.$flags, $input);
        if ($split === false) {
            return XsSequence::fromArray([]);
        }

        return XsSequence::fromArray($split);
    }

    /**
     * @param Arguments $arguments
     * @return XsSequence
     */
    public static function inScopePrefixes(Arguments $arguments): XsSequence
    {
        $listOfPrefixes = [];
        foreach ($arguments->get(0) as $element) {
            $listOfPrefixes = \array_merge(
                $listOfPrefixes,
                \array_keys(FetchNamespacesFromNode::fetch($element))
            );
        }
        return XsSequence::fromArray($listOfPrefixes);
    }

    /**
     * @param string $input
     * @param string $pattern
     * @param string $flags
     * @return string
     */
    public static function replace(string $input, string $pattern, string $replacement, string $flags = ''): string
    {
        return (string)\preg_replace('/'.$pattern.'/'.$flags, $replacement, $input);
    }

    /**
     * @param Arguments $arguments
     * @return string
     */
    public static function stringJoin(Arguments $arguments): string
    {
        $elements = $arguments->castAsSequence(0);
        $separator = (string)$arguments->castAsScalar(1);

        $result = '';

        $index = 0;
        foreach ($elements as $element) {
            if ($index > 0) {
                $result .= $separator;
            }

            $result .= $element;
            $index++;
        }

        return $result;
    }

    /**
     * @param string $uriPart
     * @return string
     */
    public static function encodeForUri(string $uriPart): string
    {
        return \rawurlencode($uriPart);
    }

    /**
     * @param Arguments $arguments
     * @return string
     */
    public static function codepointsToString(Arguments $arguments): string
    {
        $result = '';
        $sequence = $arguments->get(0);

        if (\is_numeric($sequence)) {
            return \IntlChar::chr((int)$sequence);
        }

        foreach ($arguments->castAsSequence(0) as $element) {
            $result .= \IntlChar::chr((int)$element);
        }

        return $result;
    }

    /**
     * @param Arguments $arguments
     * @return XsSequence
     */
    public static function stringToCodePoints(Arguments $arguments): XsSequence
    {
        $result = [];

        $iterator = \IntlBreakIterator::createCharacterInstance(\Locale::getDefault());
        $iterator->setText($arguments->castAsScalar(0));

        foreach ($iterator->getPartsIterator() as $char) {
            $result[] = \IntlChar::ord($char);
        }

        return XsSequence::fromArray($result);
    }
}
