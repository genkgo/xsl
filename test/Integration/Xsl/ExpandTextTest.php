<?php 
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xsl;

class ExpandTextTest extends AbstractXslTest
{
    public function testExpandText()
    {
        $this->assertEquals(
            'Ben Harper Jimi Hendrix',
            $this->transformFile('Stubs/Xsl/TextValueTemplates/expand-text.xsl')
        );
    }

    public function testExpandTextOverwrite()
    {
        $this->assertContains(
            '{artist}',
            $this->transformFile('Stubs/Xsl/TextValueTemplates/expand-text-overwrite.xsl')
        );
    }
}
