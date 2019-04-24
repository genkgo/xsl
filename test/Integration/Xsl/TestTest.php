<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xsl;

class TestTest extends AbstractXslTest
{
    public function testMatch()
    {
        $this->assertEquals('<test>Electric Ladyland</test>', $this->transformFile('Stubs/Xsl/test.xsl'));
    }
}
