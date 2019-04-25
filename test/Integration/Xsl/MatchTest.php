<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xsl;

final class MatchTest extends AbstractXslTest
{
    public function testMatch()
    {
        $this->assertContains('matched', $this->transformFile('Stubs/Xsl/match.xsl'));
    }
}
