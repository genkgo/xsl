<?php
namespace Genkgo\Xsl\Integration\Xpath;

class TextTest extends AbstractXpathTest
{
    public function testConcat()
    {
        $this->assertEquals('Hello World!', $this->transformFile('Stubs/Xpath/Text/concat.xsl'));
    }

    public function testContains()
    {
        $this->assertEquals('false', $this->transformFile('Stubs/Xpath/Text/contains.xsl', [
            'param1' => 'Hello',
            'param2' => 'World'
        ]));

        $this->assertEquals('true', $this->transformFile('Stubs/Xpath/Text/contains.xsl', [
            'param1' => 'Hello World',
            'param2' => 'World'
        ]));
    }

    public function testStringLength()
    {
        $this->assertEquals('5', $this->transformFile('Stubs/Xpath/Text/string-length.xsl', [
            'param1' => 'Hello',
        ]));

        $this->assertEquals('11', $this->transformFile('Stubs/Xpath/Text/string-length.xsl', [
            'param1' => 'Hello World',
        ]));
    }

    public function testStartsWith()
    {
        $this->assertEquals('false', $this->transformFile('Stubs/Xpath/Text/starts-with.xsl', [
            'param1' => 'Hello',
            'param2' => 'World'
        ]));

        $this->assertEquals('true', $this->transformFile('Stubs/Xpath/Text/starts-with.xsl', [
            'param1' => 'Hello World',
            'param2' => 'Hello'
        ]));
    }

    public function testEndsWith()
    {
        $this->assertEquals('false', $this->transformFile('Stubs/Xpath/Text/ends-with.xsl', [
            'param1' => 'Hello',
            'param2' => 'World'
        ]));

        $this->assertEquals('true', $this->transformFile('Stubs/Xpath/Text/ends-with.xsl', [
            'param1' => 'Hello World',
            'param2' => 'World'
        ]));
    }

    public function testIndexOf()
    {
        $this->assertEquals('false', $this->transformFile('Stubs/Xpath/Text/index-of.xsl', [
            'param1' => 'Hello',
            'param2' => 'World'
        ]));

        $this->assertEquals('7', $this->transformFile('Stubs/Xpath/Text/index-of.xsl', [
            'param1' => 'Hello World',
            'param2' => 'World'
        ]));
    }

    public function testLowerCase()
    {
        $this->assertEquals('php', $this->transformFile('Stubs/Xpath/Text/lower-case.xsl'));
    }

    public function testUpperCase()
    {
        $this->assertEquals('PHP', $this->transformFile('Stubs/Xpath/Text/upper-case.xsl'));
    }

    public function testMatches()
    {
        $this->assertEquals('true', $this->transformFile('Stubs/Xpath/Text/matches.xsl', [
            'param1' => 'helloworld',
            'param2' => 'hello world',
            'param3' => 'x'
        ]));

        $this->assertEquals('false', $this->transformFile('Stubs/Xpath/Text/matches.xsl', [
            'param1' => 'helloworld',
            'param2' => 'hello[ ]world',
            'param3' => 'x'
        ]));

        $this->assertEquals('false', $this->transformFile('Stubs/Xpath/Text/matches.xsl', [
            'param1' => 'hello world',
            'param2' => 'hello world',
            'param3' => 'x'
        ]));
    }

    public function testTokenize()
    {
        $this->assertEquals('xsl2istranspiledbygenkgo/xsl', $this->transformFile('Stubs/Xpath/Text/tokenize.xsl'));
    }

    public function testReplace()
    {
        $this->assertEquals('[1=ab][2=]cd', $this->transformFile('Stubs/Xpath/Text/replace.xsl'));
    }

    public function testTranslate()
    {
        $this->assertEquals('BAr', $this->transformFile('Stubs/Xpath/Text/translate.xsl'));
    }

    public function testSubstring()
    {
        $this->assertEquals('xsl', $this->transformFile('Stubs/Xpath/Text/substring.xsl'));
    }

    public function testSubstringAfter()
    {
        $this->assertEquals('too', $this->transformFile('Stubs/Xpath/Text/substring-after.xsl', [
            'param1' => 'tattoo',
            'param2' => 'tat'
        ]));

        $this->assertEquals('tattoo', $this->transformFile('Stubs/Xpath/Text/substring-after.xsl', [
            'param1' => 'tattoo',
            'param2' => ''
        ]));

        $this->assertEquals('', $this->transformFile('Stubs/Xpath/Text/substring-after.xsl', [
            'param1' => 'tattoo',
            'param2' => 'test'
        ]));
    }

    public function testSubstringBefore()
    {
        $this->assertEquals('tat', $this->transformFile('Stubs/Xpath/Text/substring-before.xsl', [
            'param1' => 'tattoo',
            'param2' => 'too'
        ]));

        $this->assertEquals('tattoo', $this->transformFile('Stubs/Xpath/Text/substring-before.xsl', [
            'param1' => 'tattoo',
            'param2' => ''
        ]));

        $this->assertEquals('', $this->transformFile('Stubs/Xpath/Text/substring-before.xsl', [
            'param1' => 'tattoo',
            'param2' => 'test'
        ]));
    }
}
