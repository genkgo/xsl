<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xpath;

class AggregationTest extends AbstractXpathTest
{
    public function testSum()
    {
        $this->assertEquals(1995 + 1997, $this->transformFile('Stubs/Xpath/Aggregation/sum.xsl'));
    }

    public function testCount()
    {
        $this->assertEquals(2, $this->transformFile('Stubs/Xpath/Aggregation/count.xsl'));
    }

    public function testAvg()
    {
        $this->assertEquals(1996, $this->transformFile('Stubs/Xpath/Aggregation/avg.xsl'));
    }

    public function testMax()
    {
        $this->assertEquals(1997, $this->transformFile('Stubs/Xpath/Aggregation/max.xsl'));
    }

    public function testMin()
    {
        $this->assertEquals(1995, $this->transformFile('Stubs/Xpath/Aggregation/min.xsl'));
    }

    public function testSequenceSum()
    {
        $this->assertEquals(1995 + 1997, $this->transformFile('Stubs/Xpath/Aggregation/sequence-sum.xsl'));
    }

    public function testSequenceAvg()
    {
        $this->assertEquals(1996, $this->transformFile('Stubs/Xpath/Aggregation/sequence-avg.xsl'));
    }

    public function testSequenceAvgEmpty()
    {
        $this->assertEquals('', $this->transformFile('Stubs/Xpath/Aggregation/sequence-avg-empty.xsl'));
    }
}
