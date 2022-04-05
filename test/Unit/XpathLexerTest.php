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
namespace Genkgo\Xsl\Unit;

use Genkgo\Xsl\Xpath;
use Genkgo\Xsl\AbstractTestCase;

final class XpathLexerTest extends AbstractTestCase
{
    public function testTokenize(): void
    {
        $sourcePath = '//*[@id="i"]';
        $expectedTokens = ['//', '*', '[', '@', 'id', '=', '"i"', ']'];
        $resultLexer = Xpath\Lexer::tokenize($sourcePath);
        $expectedLexer = new Xpath\Lexer($expectedTokens);

        for ($i = 0; $i < \count($expectedTokens); $i++) {
            $this->assertEquals($expectedLexer->current(), $resultLexer->current());
            $expectedLexer->next();
            $resultLexer->next();
        }
    }

    public function testNext(): void
    {
        $expectedTokens = ['/', 'bookstore', '/', 'book', '[', 'price', '>',
            '35.00', ']'];
        $resultLexer = new Xpath\Lexer($expectedTokens);

        for ($i = 0; $i < \count($expectedTokens); $i++) {
            $this->assertEquals($expectedTokens[$i], $resultLexer->current());
            $resultLexer->next();
        }
    }

    public function testCount(): void
    {
        $resultLexer = new Xpath\Lexer([]);
        $this->assertCount(0, $resultLexer);

        $resultLexer = new Xpath\Lexer(['//', '*', '[', '@', 'id', '=', '"i"', ']']);
        $this->assertCount(8, $resultLexer);
    }

    public function testSeek(): void
    {
        $expectedTokens = ['name', '(', '"some_name"', ')'];
        $resultLexer = new Xpath\Lexer($expectedTokens);
        $this->assertEquals($expectedTokens[0], $resultLexer->current());
        for ($i = 0; $i < \count($expectedTokens); $i++) {
            $resultLexer->seek($i);
            $this->assertEquals($expectedTokens[$i], $resultLexer->current());
        }
    }

    public function testBack(): void
    {
        $expectedTokens = ['..', '/', 'contents', '/', 'child', '::', 'sections'];

        $resultLexer = new Xpath\Lexer($expectedTokens);
        for ($i = 0; $i < \count($expectedTokens); $i++) {
            $resultLexer->next();
        }

        $resultLexer->prev();
        $this->assertEquals($expectedTokens[\count($expectedTokens) - 1], $resultLexer->current());
    }

    public function testIterate(): void
    {
        $expectedTokens = ['..', '/', 'contents', '/', 'child', '::', 'sections'];

        $index = 0;
        $resultLexer = new Xpath\Lexer($expectedTokens);
        foreach ($resultLexer as $resultToken) {
            $this->assertEquals($expectedTokens[$index], $resultToken);
            $this->assertEquals($index, $resultLexer->key());
            $index++;
        }
    }

    public function testInsert(): void
    {
        $expectedTokens = ['//', '*', '[', '@', 'id', '=', '"i"', ']', '/', 'book'];
        $resultLexer = new Xpath\Lexer(['//', '*', '[', '@', 'id', '=', '"i"', ']']);
        $resultLexer->insert(['/', 'book'], 8);

        $this->assertCount(10, $resultLexer);
        $this->assertEquals($expectedTokens[8], $resultLexer->peek(8));
        $this->assertEquals($expectedTokens[9], $resultLexer->peek(9));
    }
}
