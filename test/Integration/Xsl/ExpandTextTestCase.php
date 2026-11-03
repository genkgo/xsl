<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xsl;

class ExpandTextTestCase extends AbstractXslTestCase
{
    public function testExpandText(): void
    {
        $this->assertEquals(
            'the cds from Ben Harper, Jimi Hendrix are from { 1995, 1997 }',
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
