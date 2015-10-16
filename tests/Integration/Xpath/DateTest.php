<?php
namespace Genkgo\Xsl\Integration\Xpath;

class DateTest extends AbstractXpathTest
{
    public function testCurrentTime()
    {
        $this->assertEquals(1, preg_match(
            '/[0-9]{2}\:[0-9]{2}\:[0-9]{2}\+[0-9]{2}\:[0-9]{2}/',
            $this->transformFile('Stubs/Xpath/Date/current-time.xsl')
        ));
    }

    public function testCurrentDate()
    {
        $this->assertEquals(1, preg_match(
            '/[0-9]{4}\-[0-9]{2}\-[0-9]{2}\+[0-9]{2}\:[0-9]{2}/',
            $this->transformFile('Stubs/Xpath/Date/current-date.xsl')
        ));
    }

    public function testCurrentDateTime()
    {
        $this->assertEquals(1, preg_match(
            '/[0-9]{4}\-[0-9]{2}\-[0-9]{2} [0-9]{2}\:[0-9]{2}\:[0-9]{2}\+[0-9]{2}\:[0-9]{2}/',
            $this->transformFile('Stubs/Xpath/Date/current-dateTime.xsl')
        ));
    }

}
