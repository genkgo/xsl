<?php
declare(strict_types=1);

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

final class Lexer implements Iterator, SeekableIterator, Countable
{
    /**
     * @var array|string[]
     */
    private $tokens;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @param array $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * @param string $source
     * @return Lexer
     */
    public static function tokenize(string $source)
    {
        $tokens = [];
        \preg_match_all(self::compileTokenRegEx(), $source, $tokens);
        $tokens = $tokens[0];

        return new static($tokens);
    }

    /**
     * @return string
     */
    private static function compileTokenRegEx()
    {
        $tokens = [
            '\$?(?:(?![0-9-])[\w-]+:)?(?![0-9-])[\w-]+', // Nodename (possibly with namespace) or variable.
            '\/\/', // Double slash.
            '\.\.', // Double dot.
            '::', // Double colon.
            '\d+(?:\.\d*)?', // Number starting with digit.
            '\.\d+', // Number starting with decimal point.
            '"[^"]*"', // Double quoted string.
            '\'([^\\\']|\'\')*\'', // Single quoted string.
            '[!<>]=', // Operators
            '\s+', // Whitespaces.
            '.', // Any single character.
        ];

        return '/' . \implode('|', $tokens) . '/';
    }

    /**
     * @param array|string[] $tokens
     * @param int $position
     */
    public function insert(array $tokens, $position)
    {
        \array_splice($this->tokens, (int)$position, 0, $tokens);
    }

    /**
     * @return string
     */
    public function current(): string
    {
        return $this->tokens[$this->position];
    }

    /**
     * @return void
     */
    public function next(): void
    {
        $this->position++;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @return boolean
     */
    public function valid(): bool
    {
        return isset($this->tokens[$this->position]);
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @param  int  $position
     * @return void
     */
    public function seek($position): void
    {
        $this->position = $position;
    }

    /**
     * @param int $position
     * @return string
     */
    public function peek(int $position): string
    {
        return $this->tokens[$position] ?? '';
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->tokens);
    }
    
    public function prev()
    {
        $this->position--;
    }
}
