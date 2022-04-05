<?php
declare(strict_types=1);

namespace Genkgo\Xsl;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    private string $oldCwd;

    protected function setUp(): void
    {
        $this->oldCwd = (string)\getcwd();
        \chdir(__DIR__);
    }

    protected function tearDown(): void
    {
        \chdir($this->oldCwd);
    }
}
