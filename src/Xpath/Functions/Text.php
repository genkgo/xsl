<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath\Functions;

use DOMElement;
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
    public static function tokenize(string $input, string $pattern, $flags = ''): XsSequence
    {
        $split = \preg_split('/'.$pattern.'/'.$flags, $input);
        if ($split === false) {
            return XsSequence::fromArray([]);
        }

        return XsSequence::fromArray($split);
    }

    /**
     * @param array $elements
     * @return XsSequence
     */
    public static function inScopePrefixes(array $elements): XsSequence
    {
        Assert::assertArray($elements);
        $listOfPrefixes = [];
        foreach ($elements as $element) {
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
     * @param array|DOMElement[] $elements
     * @param string $separator
     * @return string
     */
    public static function stringJoin(array $elements, string $separator): string
    {
        $result = '';

        $index = 0;
        foreach ($elements as $element) {
            if ($index > 0) {
                $result .= $separator;
            }

            $result .= $element->nodeValue;
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
     * @param mixed $sequence
     * @return string
     */
    public static function codepointsToString($sequence): string
    {
        $result = '';

        if (\is_numeric($sequence)) {
            return \IntlChar::chr((int)$sequence);
        }

        foreach ($sequence as $element) {
            $result .= \IntlChar::chr((int)$element->nodeValue);
        }

        return $result;
    }

    /**
     * @param string $string
     * @return XsSequence
     */
    public static function stringToCodePoints(string $string): XsSequence
    {
        $result = [];

        $iterator = \IntlBreakIterator::createCharacterInstance(\Locale::getDefault());
        $iterator->setText($string);

        foreach ($iterator->getPartsIterator() as $char) {
            $result[] = \IntlChar::ord($char);
        }

        return XsSequence::fromArray($result);
    }
}
