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
namespace Genkgo\Xsl;

class XpathLexerTest extends AbstractTestCase
{
    public function testTokenize()
    {
        $sourcePath = '//*[@id="i"]';
        $expectedTokens = ['//', '*', '[', '@', 'id', '=', '"i"', ']'];
        $resultLexer = XpathLexer::tokenize($sourcePath);
        $expectedLexer = new XpathLexer($expectedTokens);

        for ($i = 0; $i < count($expectedTokens); $i++) {
            $this->assertEquals($expectedLexer->current(), $resultLexer->current());
            $expectedLexer->next();
            $resultLexer->next();
        }
    }

    public function testNext()
    {
        $expectedTokens = ['/', 'bookstore', '/', 'book', '[', 'price', '>',
            '35.00', ']'];
        $resultLexer = new XpathLexer($expectedTokens);

        for ($i = 0; $i < count($expectedTokens); $i++) {
            $this->assertEquals($expectedTokens[$i], $resultLexer->current());
            $resultLexer->next();
        }
    }

    public function testCount()
    {
        $resultLexer = new XpathLexer([]);
        $this->assertCount(0, $resultLexer);

        $resultLexer = new XpathLexer(['//', '*', '[', '@', 'id', '=', '"i"', ']']);
        $this->assertCount(8, $resultLexer);
    }

    public function testSeek()
    {
        $expectedTokens = ['name', '(', '"some_name"', ')'];
        $resultLexer = new XpathLexer($expectedTokens);
        $this->assertEquals($expectedTokens[0], $resultLexer->current());
        for ($i = 0; $i < count($expectedTokens); $i++) {
            $resultLexer->seek($i);
            $this->assertEquals($expectedTokens[$i], $resultLexer->current());
        }
    }

    public function testBack()
    {
        $expectedTokens = ['..', '/', 'contents', '/', 'child', '::', 'sections'];

        $resultLexer = new XpathLexer($expectedTokens);
        for ($i = 0; $i < count($expectedTokens); $i++) {
            $resultLexer->next();
        }

        $resultLexer->prev();
        $this->assertEquals($expectedTokens[count($expectedTokens) - 1], $resultLexer->current());
    }

    public function testIterate()
    {
        $expectedTokens = ['..', '/', 'contents', '/', 'child', '::', 'sections'];

        $index = 0;
        $resultLexer = new XpathLexer($expectedTokens);
        foreach ($resultLexer as $resultToken) {
            $this->assertEquals($expectedTokens[$index], $resultToken);
            $this->assertEquals($index, $resultLexer->key());
            $index++;
        }
    }

    public function testWhiteSpace()
    {
        $source = "\nconcat(a, b)";
        $expectedTokens = ['concat', '(', 'a', ',', 'b', ')'];

        $resultLexer = XpathLexer::tokenize($source);
        foreach ($resultLexer as $resultToken) {
            $this->assertEquals($expectedTokens[$resultLexer->key()], $resultToken);
        }
    }
}
