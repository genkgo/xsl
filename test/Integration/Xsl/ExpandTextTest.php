<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xsl;

class ExpandTextTest extends AbstractXslTest
{
    public function testExpandText(): void
    {
        $this->assertEquals(
            'Ben Harper Jimi Hendrix',
            $this->transformFile('Stubs/Xsl/TextValueTemplates/expand-text.xsl')
        );
    }

    public function testExpandTextOverwrite(): void
    {
        $this->assertStringContainsString(
            '{artist}',
            $this->transformFile('Stubs/Xsl/TextValueTemplates/expand-text-overwrite.xsl')
        );
    }
}
