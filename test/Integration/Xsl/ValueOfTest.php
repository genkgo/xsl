<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xsl;

class ValueOfTest extends AbstractXslTest
{
    public function testSeparator(): void
    {
        $this->assertEquals('xsl2,is,transpiled,by,genkgo/xsl', $this->transformFile('Stubs/Xsl/ValueOf/separator.xsl'));
    }

    public function testSequenceMultipleItems(): void
    {
        $this->assertEquals('Ben Harper,Jimi Hendrix', $this->transformFile('Stubs/Xsl/ValueOf/sequence-multiple.xsl'));
    }

    public function testSequenceSingleItem(): void
    {
        $this->assertStringNotContainsString(',', $this->transformFile('Stubs/Xsl/ValueOf/sequence-single.xsl'));
    }

    public function testWithoutSeparator(): void
    {
        $this->assertEquals('xsl2 is transpiled by genkgo/xsl', $this->transformFile('Stubs/Xsl/ValueOf/without-separator.xsl'));
    }
}
