<?php
namespace Genkgo\Xsl\Integration\Xsl;

class MatchTest extends AbstractXslTest
{
    public function testMatch()
    {
        $this->assertContains('matched', $this->transformFile('Stubs/Xsl/match.xsl'));
    }
}
