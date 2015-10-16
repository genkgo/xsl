<?php
namespace Genkgo\Xsl\Integration\Xsl;

class ValueOfSeparatorTest extends AbstractXslTest
{
    public function testSeparator()
    {
        $this->assertEquals('xsl2,is,transpiled,by,genkgo/xsl', $this->transformFile('Stubs/Xsl/value-of-separator.xsl'));
    }
}
