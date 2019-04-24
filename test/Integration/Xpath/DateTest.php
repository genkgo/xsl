<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xpath;

class DateTest extends AbstractXpathTest
{
    public function testCurrentTime()
    {
        $this->assertEquals(1, \preg_match(
            '/[0-9]{2}\:[0-9]{2}\:[0-9]{2}\+[0-9]{2}\:[0-9]{2}/',
            $this->transformFile('Stubs/Xpath/Date/current-time.xsl')
        ));
    }

    public function testCurrentDate()
    {
        $this->assertEquals(1, \preg_match(
            '/[0-9]{4}\-[0-9]{2}\-[0-9]{2}\+[0-9]{2}\:[0-9]{2}/',
            $this->transformFile('Stubs/Xpath/Date/current-date.xsl')
        ));
    }

    public function testCurrentDateTime()
    {
        $this->assertEquals(1, \preg_match(
            '/[0-9]{4}\-[0-9]{2}\-[0-9]{2}T[0-9]{2}\:[0-9]{2}\:[0-9]{2}\+[0-9]{2}\:[0-9]{2}/',
            $this->transformFile('Stubs/Xpath/Date/current-dateTime.xsl')
        ));
    }

    public function testYearFromDate()
    {
        $this->assertEquals(\date('Y'), $this->transformFile('Stubs/Xpath/Date/year-from-date.xsl'));
    }

    public function testYearFromDateTime()
    {
        $this->assertEquals(\date('Y'), $this->transformFile('Stubs/Xpath/Date/year-from-dateTime.xsl'));
    }

    public function testYearsFromDurations()
    {
        $this->assertEquals(1, $this->transformFile('Stubs/Xpath/Date/years-from-duration.xsl'));
    }

    public function testMonthFromDate()
    {
        $this->assertEquals(6, $this->transformFile('Stubs/Xpath/Date/month-from-date.xsl'));
    }

    public function testMonthFromDateTime()
    {
        $this->assertEquals(7, $this->transformFile('Stubs/Xpath/Date/month-from-dateTime.xsl'));
    }

    public function testDayFromDate()
    {
        $this->assertEquals(6, $this->transformFile('Stubs/Xpath/Date/day-from-date.xsl'));
    }

    public function testDayFromDateTime()
    {
        $this->assertEquals(7, $this->transformFile('Stubs/Xpath/Date/day-from-dateTime.xsl'));
    }

    public function testHoursFromTime()
    {
        $this->assertEquals(19, $this->transformFile('Stubs/Xpath/Date/hours-from-time.xsl'));
    }

    public function testHoursFromDateTime()
    {
        $this->assertEquals(5, $this->transformFile('Stubs/Xpath/Date/hours-from-dateTime.xsl'));
    }

    public function testMinutesFromTime()
    {
        $this->assertEquals(59, $this->transformFile('Stubs/Xpath/Date/minutes-from-time.xsl'));
    }

    public function testMinutesFromDateTime()
    {
        $this->assertEquals(8, $this->transformFile('Stubs/Xpath/Date/minutes-from-dateTime.xsl'));
    }

    public function testSecondsFromTime()
    {
        $this->assertEquals(20, $this->transformFile('Stubs/Xpath/Date/seconds-from-time.xsl'));
    }

    public function testSecondsFromDateTime()
    {
        $this->assertEquals(10, $this->transformFile('Stubs/Xpath/Date/seconds-from-dateTime.xsl'));
    }
}
