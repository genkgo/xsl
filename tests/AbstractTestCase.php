<?php
namespace Genkgo\Xsl;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
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
