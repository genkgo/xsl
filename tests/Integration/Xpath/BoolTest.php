<?php
namespace Genkgo\Xsl\Integration\Xpath;

class BoolTest extends AbstractXpathTest
{
    public function testBooleanText()
    {
        $this->assertEquals('true', $this->transformFile('Stubs/Xpath/Bool/boolean-text.xsl'));
    }

    public function testTrue()
    {
        $this->assertEquals('true', $this->transformFile('Stubs/Xpath/Bool/true.xsl'));
    }

    public function testFalse()
    {
        $this->assertEquals('false', $this->transformFile('Stubs/Xpath/Bool/false.xsl'));
    }
}
