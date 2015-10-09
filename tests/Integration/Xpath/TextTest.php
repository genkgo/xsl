<?php
namespace Genkgo\Xsl\Integration\Xpath;

class TextTest extends AbstractXpathTest {

    public function testConcat () {
        $this->assertEquals('Hello World!', $this->transformFile('Stubs/Xpath/String/concat.xsl'));
    }

    public function testContains () {
        $this->assertEquals('false', $this->transformFile('Stubs/Xpath/String/contains.xsl', [
            'param1' => 'Hello',
            'param2' => 'World'
        ]));

        $this->assertEquals('true', $this->transformFile('Stubs/Xpath/String/contains.xsl', [
            'param1' => 'Hello World',
            'param2' => 'World'
        ]));
    }

    public function testEndsWith () {
        $this->assertEquals('false', $this->transformFile('Stubs/Xpath/String/ends-with.xsl', [
            'param1' => 'Hello',
            'param2' => 'World'
        ]));

        $this->assertEquals('true', $this->transformFile('Stubs/Xpath/String/ends-with.xsl', [
            'param1' => 'Hello World',
            'param2' => 'World'
        ]));
    }

    public function testIndexOf () {
        $this->assertEquals('false', $this->transformFile('Stubs/Xpath/String/index-of.xsl', [
            'param1' => 'Hello',
            'param2' => 'World'
        ]));

        $this->assertEquals('7', $this->transformFile('Stubs/Xpath/String/index-of.xsl', [
            'param1' => 'Hello World',
            'param2' => 'World'
        ]));
    }

    public function testLowerCase () {
        $this->assertEquals('php', $this->transformFile('Stubs/Xpath/String/lower-case.xsl'));
    }

    public function testUpperCase () {
        $this->assertEquals('PHP', $this->transformFile('Stubs/Xpath/String/upper-case.xsl'));
    }

    public function testMatches () {
        $this->assertEquals('true', $this->transformFile('Stubs/Xpath/String/matches.xsl', [
            'param1' => 'helloworld',
            'param2' => 'hello world',
            'param3' => 'x'
        ]));

        $this->assertEquals('false', $this->transformFile('Stubs/Xpath/String/matches.xsl', [
            'param1' => 'helloworld',
            'param2' => 'hello[ ]world',
            'param3' => 'x'
        ]));

        $this->assertEquals('false', $this->transformFile('Stubs/Xpath/String/matches.xsl', [
            'param1' => 'hello world',
            'param2' => 'hello world',
            'param3' => 'x'
        ]));

        $this->markTestIncomplete('One method not passing yet');

        $this->assertEquals('true', $this->transformFile('Stubs/Xpath/String/matches.xsl', [
            'param1' => 'hello world',
            'param2' => 'hello\ sworld',
            'param3' => 'x'
        ]));
    }

    public function testTokenize () {
        $this->assertEquals('xsl2istranspiledbygenkgo/xsl', $this->transformFile('Stubs/Xpath/String/tokenize.xsl'));
    }

    public function testTranslate () {
        $this->assertEquals('BAr', $this->transformFile('Stubs/Xpath/String/translate.xsl'));
    }

    public function testSubstring () {
        $this->assertEquals('xsl', $this->transformFile('Stubs/Xpath/String/substring.xsl'));
    }

    public function testSubstringAfter () {
        $this->assertEquals('too', $this->transformFile('Stubs/Xpath/String/substring-after.xsl', [
            'param1' => 'tattoo',
            'param2' => 'tat'
        ]));

        $this->assertEquals('tattoo', $this->transformFile('Stubs/Xpath/String/substring-before.xsl', [
            'param1' => 'tattoo',
            'param2' => ''
        ]));

        $this->assertEquals('', $this->transformFile('Stubs/Xpath/String/substring-before.xsl', [
            'param1' => 'tattoo',
            'param2' => 'test'
        ]));
    }

    public function testSubstringBefore () {
        $this->assertEquals('tat', $this->transformFile('Stubs/Xpath/String/substring-before.xsl', [
            'param1' => 'tattoo',
            'param2' => 'too'
        ]));

        $this->assertEquals('tattoo', $this->transformFile('Stubs/Xpath/String/substring-before.xsl', [
            'param1' => 'tattoo',
            'param2' => ''
        ]));

        $this->assertEquals('', $this->transformFile('Stubs/Xpath/String/substring-before.xsl', [
            'param1' => 'tattoo',
            'param2' => 'test'
        ]));
    }

}