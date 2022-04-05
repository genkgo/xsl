<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xpath;

class MathTest extends AbstractXpathTest
{
    public function testAbs(): void
    {
        $this->assertEquals(5, $this->transformFile('Stubs/Xpath/Math/abs.xsl'));
    }

    public function testCeiling(): void
    {
        $this->assertEquals(5, $this->transformFile('Stubs/Xpath/Math/ceiling.xsl'));
    }

    public function testCount(): void
    {
        $this->assertEquals(1, $this->transformFile('Stubs/Xpath/Math/count.xsl'));
    }

    public function testFloor(): void
    {
        $this->assertEquals(4, $this->transformFile('Stubs/Xpath/Math/floor.xsl'));
    }

    public function testRound(): void
    {
        $this->assertEquals(5, $this->transformFile('Stubs/Xpath/Math/round.xsl'));
    }

    public function testRoundHalfToEven(): void
    {
        $this->assertEquals(6, $this->transformFile('Stubs/Xpath/Math/round-half-to-even.xsl', [
            'number' => 5.5,
            'precision' => 0,
        ]));

        $this->assertEquals(5.93, $this->transformFile('Stubs/Xpath/Math/round-half-to-even.xsl', [
            'number' => 5.934,
            'precision' => 2,
        ]));

        $this->assertEquals(5.6, $this->transformFile('Stubs/Xpath/Math/round-half-to-even.xsl', [
            'number' => 5.595,
            'precision' => 2,
        ]));
    }
}
