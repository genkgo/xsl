<?php
/**
 * @license
 * The MIT License
 *
 * Copyright (c) 2007 Cybozu Labs, Inc.
 * Copyright (c) 2012 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 */
/**
 * @author moz@google.com (Michael Zhou)
 */
namespace Genkgo\Xsl\Xpath;

use Countable;
use Iterator;
use SeekableIterator;

/**
 * Class Lexer
 * @package Genkgo\Xsl\Xpath
 */
class Lexer implements Iterator, SeekableIterator, Countable
{
    /**
     * @var
     */
    private $tokens;
    /**
     * @var int
     */
    private $position = 0;

    /**
     *
     */
    const LEADING_WHITESPACE = ' /^\s/';

    /**
     * @param $tokens
     */
    public function __construct($tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * @param $source
     * @return Lexer
     */
    public static function tokenize($source)
    {
        $tokens = [];
        preg_match_all(static::compileTokenRegEx(), $source, $tokens);
        $tokens = $tokens[0];

        // Removes tokens starting with whitespace from the array.
        for ($i = 0; $i < count($tokens); $i++) {
            if (preg_match(self::LEADING_WHITESPACE, $tokens[$i]) === 1) {
                array_splice($tokens, $i, 1);
            }
        }

        return new static($tokens);
    }

    /**
     * @return string
     */
    private static function compileTokenRegEx()
    {
        $tokens = [
            '\\$?(?:(?![0-9-])[\\w-]+:)?(?![0-9-])[\\w-]+', // Nodename (possibly with namespace) or variable.
            '\\/\\/', // Double slash.
            '\\.\\.', // Double dot.
            '::', // Double colon.
            '\\d+(?:\\.\\d*)?', // Number starting with digit.
            '\\.\\d+', // Number starting with decimal point.
            '"[^"]*"', // Double quoted string.
            '\'[^\']*\'', // Single quoted string.
            '[!<>]=', // Operators
            '\\s+', // Whitespaces.
            '.', // Any single character.
        ];

        return '/' . implode('|', $tokens) . '/';
    }

    /**
     * @return string
     */
    public function current()
    {
        return $this->tokens[$this->position];
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return boolean
     */
    public function valid()
    {
        return isset($this->tokens[$this->position]);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @param int $position
     * @return void
     */
    public function seek($position)
    {
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->tokens);
    }

    /**
     *
     */
    public function prev()
    {
        $this->position--;
    }
}
