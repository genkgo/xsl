<?php
namespace Genkgo\Xsl\Integration\Xpath;

class StringTest extends AbstractXpathTest {

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

}