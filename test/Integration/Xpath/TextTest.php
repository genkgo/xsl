<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xpath;

use Genkgo\Xsl\Exception\TransformationException;

class TextTest extends AbstractXpathTest
{
    public function testConcat(): void
    {
        $this->assertEquals('Hello World!', $this->transformFile('Stubs/Xpath/Text/concat.xsl'));
    }

    public function testContains(): void
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

    public function testStringLength(): void
    {
        $this->assertEquals('5', $this->transformFile('Stubs/Xpath/Text/string-length.xsl', [
            'param1' => 'Hello',
        ]));

        $this->assertEquals('11', $this->transformFile('Stubs/Xpath/Text/string-length.xsl', [
            'param1' => 'Hello World',
        ]));
    }

    public function testStartsWith(): void
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

    public function testEndsWith(): void
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

    public function testLowerCase(): void
    {
        $this->assertEquals('php', $this->transformFile('Stubs/Xpath/Text/lower-case.xsl'));
    }

    public function testLowerCaseMultipleMatches(): void
    {
        $this->assertEquals('guitar', $this->transformFile('Stubs/Xpath/Text/lower-case-multiple-matches.xsl'));
    }

    public function testUpperCase(): void
    {
        $this->assertEquals('PHP', $this->transformFile('Stubs/Xpath/Text/upper-case.xsl'));
    }

    public function testMatches(): void
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

    public function testTokenize(): void
    {
        $this->assertEquals('|xsl2||is||transpiled||by||genkgo/xsl|', $this->transformFile('Stubs/Xpath/Text/tokenize.xsl'));
    }

    public function testTokenizeQuote(): void
    {
        $this->assertEquals('5', $this->transformFile('Stubs/Xpath/Text/tokenize-quote.xsl'));
    }

    public function testInScopePrefixes(): void
    {
        $this->assertEquals('|xml||test2||test1|', $this->transformFile('Stubs/Xpath/Text/in-scope-prefixes.xsl'));
    }

    public function testStringJoin(): void
    {
        $this->assertEquals('xsl2,is,transpiled,by,genkgo/xsl', $this->transformFile('Stubs/Xpath/Text/string-join.xsl'));
    }

    public function testReplace(): void
    {
        $this->assertEquals('[1=ab][2=]cd', $this->transformFile('Stubs/Xpath/Text/replace.xsl', [
            'param1' => 'abcd',
            'param2' => '(ab)|(a)',
            'param3' => '[1=$1][2=$2]',
        ]));
    }

    public function testReplaceMultiple(): void
    {
        $this->assertEquals('Be**a Ita*ia', $this->transformFile('Stubs/Xpath/Text/replace.xsl', [
            'param1' => 'Bella Italia',
            'param2' => 'l',
            'param3' => '*',
        ]));
    }

    public function testReplaceFlagsQ(): void
    {
        $this->assertEquals('Bella*Italia', $this->transformFile('Stubs/Xpath/Text/replace.xsl', [
            'param1' => 'Bella.Italia',
            'param2' => '.',
            'param3' => '*',
            'param4' => 'q',
        ]));
    }

    public function testReplaceInvalidFlag(): void
    {
        $this->expectException(TransformationException::class);

        $this->transformFile('Stubs/Xpath/Text/replace.xsl', [
            'param1' => '',
            'param2' => '',
            'param3' => '',
            'param4' => 'a',
        ]);
    }

    public function testReplaceEscapeCharacters(): void
    {
        $this->assertEquals('Bella Italia', $this->transformFile('Stubs/Xpath/Text/replace.xsl', [
            'param1' => 'Bella.Italia',
            'param2' => '\.',
            'param3' => ' ',
        ]));
    }

    public function testTranslate(): void
    {
        $this->assertEquals('BAr', $this->transformFile('Stubs/Xpath/Text/translate.xsl'));
    }

    public function testSubstring(): void
    {
        $this->assertEquals('xsl', $this->transformFile('Stubs/Xpath/Text/substring.xsl'));
    }

    public function testSubstringAfter(): void
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

    public function testSubstringBefore(): void
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

    public function testNormalizeSpace(): void
    {
        $this->assertEquals('Hello World!', $this->transformFile('Stubs/Xpath/Text/normalize-space.xsl', [
            'param1' => '   Hello  World!  '
        ]));
    }

    public function testEncodeForUri(): void
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

    public function testStringToCodePoints(): void
    {
        $this->assertEquals(
            '104 101 108 108 111 32 119 111 114 108 100',
            $this->transformFile('Stubs/Xpath/Text/string-to-codepoints.xsl', [
                'param1' => 'hello world'
            ])
        );
    }

    public function testCodePointsToString(): void
    {
        $this->assertEquals(
            'hello world hello',
            $this->transformFile('Stubs/Xpath/Text/codepoints-to-string.xsl')
        );
    }

    public function testCompare(): void
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
