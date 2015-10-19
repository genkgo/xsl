<?php
namespace Genkgo\Xsl\Integration\Xsl;

class CurlyBracesTest extends AbstractXslTest
{
    public function testCurlyBraces()
    {
        $result = $this->transformFile('Stubs/Xsl/curly-braces.xsl');

        $this->assertContains('#Ladyland', $result);
        $this->assertContains('#{substring-after(title, \'Electric \')}', $result);
    }
}
