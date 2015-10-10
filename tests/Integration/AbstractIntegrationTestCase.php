<?php
namespace Genkgo\Xsl\Integration;

use Genkgo\Xsl\AbstractTestCase;

abstract class AbstractIntegrationTestCase extends AbstractTestCase
{
    private $oldCwd;

    public function setUp()
    {
        $this->oldCwd = getcwd();
        chdir(dirname(__DIR__));
    }

    public function tearDown()
    {
        chdir($this->oldCwd);
    }
}
