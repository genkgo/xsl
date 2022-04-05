<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xpath;

class BoolTest extends AbstractXpathTest
{
    public function testBooleanText(): void
    {
        $this->assertEquals('true', $this->transformFile('Stubs/Xpath/Bool/boolean-text.xsl'));
    }

    public function testTrue(): void
    {
        $this->assertEquals('true', $this->transformFile('Stubs/Xpath/Bool/true.xsl'));
    }

    public function testFalse(): void
    {
        $this->assertEquals('false', $this->transformFile('Stubs/Xpath/Bool/false.xsl'));
    }
}
