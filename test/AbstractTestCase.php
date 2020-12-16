<?php
declare(strict_types=1);

namespace Genkgo\Xsl;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    private $oldCwd;

    protected function setUp(): void
    {
        $this->oldCwd = \getcwd();
        \chdir(__DIR__);
    }

    protected function tearDown(): void
    {
        \chdir($this->oldCwd);
    }
}
