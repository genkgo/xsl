<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Unit;

use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\Cache\NullCache;
use Genkgo\Xsl\ProcessorFactory;
use Genkgo\Xsl\Schema\XmlSchema;

final class FactoryTest extends AbstractTestCase
{
    public function testNewInstances(): void
    {
        $factory = new ProcessorFactory(new NullCache(), [new XmlSchema()]);

        $processor1 = $factory->newProcessor();
        $processor2 = $factory->newProcessor();

        $this->assertNotSame($processor1, $processor2);
    }
}
