<?php
namespace Genkgo\Xsl;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    private $oldCwd;

    public function setUp()
    {
        $this->oldCwd = getcwd();
        chdir(__DIR__);
    }

    public function tearDown()
    {
        chdir($this->oldCwd);
    }
}
