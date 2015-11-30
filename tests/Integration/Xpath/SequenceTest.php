<?php
namespace Genkgo\Xsl\Integration\Xpath;

class SequenceTest extends AbstractXpathTest
{
    public function testConstructorString()
    {
        $result = $this->transformFile('Stubs/Xpath/Sequence/constructor-string.xsl');
        $this->assertContains('a b c', $result);
    }

    public function testConstructorInteger()
    {
        $result = $this->transformFile('Stubs/Xpath/Sequence/constructor-integer.xsl');
        $this->assertContains('1 2 3', $result);
    }

    public function testReverse()
    {
        $this->assertEquals(
            'genkgo/xsl by transpiled is xsl2',
            $this->transformFile('Stubs/Xpath/Sequence/reverse.xsl')
        );
    }

    public function testInsertBefore()
    {
        $this->assertEquals(
            'xsl2 and xpath2 is transpiled by genkgo/xsl',
            $this->transformFile('Stubs/Xpath/Sequence/insert-before.xsl')
        );
    }

    public function testRemove()
    {
        $this->assertEquals(
            'xsl2 transpiled by genkgo/xsl',
            $this->transformFile('Stubs/Xpath/Sequence/remove.xsl')
        );
    }

    public function testSubsequenceOffset()
    {
        $this->assertEquals(
            'transpiled by genkgo/xsl',
            $this->transformFile('Stubs/Xpath/Sequence/subsequence-offset.xsl')
        );
    }

    public function testSubsequenceFromLength()
    {
        $this->assertEquals(
            'transpiled by',
            $this->transformFile('Stubs/Xpath/Sequence/subsequence-length.xsl')
        );
    }
}
