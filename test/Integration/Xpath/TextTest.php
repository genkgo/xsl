<?php
declare(strict_types=1);

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

    public function testLowerCase()
    {
        $this->assertEquals('php', $this->transformFile('Stubs/Xpath/Text/lower-case.xsl'));
    }

    public function testLowerCaseMultipleMatches()
    {
        $this->assertEquals('guitar', $this->transformFile('Stubs/Xpath/Text/lower-case-multiple-matches.xsl'));
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
        $this->assertEquals('|xsl2||is||transpiled||by||genkgo/xsl|', $this->transformFile('Stubs/Xpath/Text/tokenize.xsl'));
    }

    public function testInScopePrefixes()
    {
        $this->assertEquals('|xml||test2||test1|', $this->transformFile('Stubs/Xpath/Text/in-scope-prefixes.xsl'));
    }

    public function testStringJoin()
    {
        $this->assertEquals('xsl2,is,transpiled,by,genkgo/xsl', $this->transformFile('Stubs/Xpath/Text/string-join.xsl'));
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

    public function testNormalizeSpace()
    {
        $this->assertEquals('Hello World!', $this->transformFile('Stubs/Xpath/Text/normalize-space.xsl', [
            'param1' => '   Hello  World!  '
        ]));
    }

    public function testEncodeForUri()
    {
        $this->assertEquals(
            'http%3A%2F%2Fwww.example.com%2F00%2FWeather%2FCA%2FLos%2520Angeles%23ocean',
            $this->transformFile('Stubs/Xpath/Text/encode-for-uri.xsl', [
                'param1' => 'http://www.example.com/00/Weather/CA/Los%20Angeles#ocean'
            ])
        );
        $this->assertEquals(
            '~b%C3%A9b%C3%A9',
            $this->transformFile('Stubs/Xpath/Text/encode-for-uri.xsl', [
                'param1' => '~bébé'
            ])
        );
        $this->assertEquals(
            '100%25%20organic',
            $this->transformFile('Stubs/Xpath/Text/encode-for-uri.xsl', [
                'param1' => '100% organic'
            ])
        );
    }

    public function testStringToCodePoints()
    {
        $this->assertEquals(
            '104 101 108 108 111 32 119 111 114 108 100',
            $this->transformFile('Stubs/Xpath/Text/string-to-codepoints.xsl', [
                'param1' => 'hello world'
            ])
        );
    }

    public function testCodePointsToString()
    {
        $this->assertEquals(
            'hello world hello',
            $this->transformFile('Stubs/Xpath/Text/codepoints-to-string.xsl')
        );
    }

    public function testCompare()
    {
        $this->assertSame('0', $this->transformFile('Stubs/Xpath/Text/compare.xsl', [
            'param1' => 'hello',
            'param2' => 'hello'
        ]));

        $this->assertSame('-1', $this->transformFile('Stubs/Xpath/Text/compare.xsl', [
            'param1' => 'Hello World',
            'param2' => 'World'
        ]));

        $this->assertSame('1', $this->transformFile('Stubs/Xpath/Text/compare.xsl', [
            'param1' => 'World',
            'param2' => 'Hello World'
        ]));
    }
}
