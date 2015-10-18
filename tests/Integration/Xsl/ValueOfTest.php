<?php
namespace Genkgo\Xsl\Integration\Xsl;

class ValueOfTest extends AbstractXslTest
{
    public function testSeparator()
    {
        $this->assertEquals('xsl2,is,transpiled,by,genkgo/xsl', $this->transformFile('Stubs/Xsl/ValueOf/separator.xsl'));
    }

    public function testSequenceMultipleItems()
    {
        $this->assertEquals('Ben Harper,Jimi Hendrix', $this->transformFile('Stubs/Xsl/ValueOf/sequence-multiple.xsl'));
    }

    public function testSequenceSingleItem()
    {
        $this->assertNotContains(',', $this->transformFile('Stubs/Xsl/ValueOf/sequence-single.xsl'));
    }

    public function testWithoutSeparator()
    {
        $this->assertEquals('xsl2 is transpiled by genkgo/xsl', $this->transformFile('Stubs/Xsl/ValueOf/without-separator.xsl'));
    }
}
