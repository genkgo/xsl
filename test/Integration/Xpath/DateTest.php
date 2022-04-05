<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xpath;

class DateTest extends AbstractXpathTest
{
    public function testCurrentTime(): void
    {
        $this->assertMatchesRegularExpression(
            '/[0-9]{2}\:[0-9]{2}\:[0-9]{2}\+[0-9]{2}\:[0-9]{2}/',
            $this->transformFile(
                'Stubs/Xpath/Date/current-time.xsl'
            )
        );
    }

    public function testCurrentDate(): void
    {
        $this->assertMatchesRegularExpression(
            '/[0-9]{4}\-[0-9]{2}\-[0-9]{2}\+[0-9]{2}\:[0-9]{2}/',
            $this->transformFile(
                'Stubs/Xpath/Date/current-date.xsl'
            )
        );
    }

    public function testCurrentDateTime(): void
    {
        $this->assertMatchesRegularExpression(
            '/[0-9]{4}\-[0-9]{2}\-[0-9]{2}T[0-9]{2}\:[0-9]{2}\:[0-9]{2}\+[0-9]{2}\:[0-9]{2}/',
            $this->transformFile(
                'Stubs/Xpath/Date/current-dateTime.xsl'
            )
        );
    }

    public function testYearFromDate(): void
    {
        $this->assertEquals(\date('Y'), $this->transformFile('Stubs/Xpath/Date/year-from-date.xsl'));
    }

    public function testYearFromDateTime(): void
    {
        $this->assertEquals(\date('Y'), $this->transformFile('Stubs/Xpath/Date/year-from-dateTime.xsl'));
    }

    public function testYearsFromDurations(): void
    {
        $this->assertEquals(1, $this->transformFile('Stubs/Xpath/Date/years-from-duration.xsl'));
    }

    public function testMonthFromDate(): void
    {
        $this->assertEquals(6, $this->transformFile('Stubs/Xpath/Date/month-from-date.xsl'));
    }

    public function testMonthFromDateTime(): void
    {
        $this->assertEquals(7, $this->transformFile('Stubs/Xpath/Date/month-from-dateTime.xsl'));
    }

    public function testDayFromDate(): void
    {
        $this->assertEquals(6, $this->transformFile('Stubs/Xpath/Date/day-from-date.xsl'));
    }

    public function testDayFromDateTime(): void
    {
        $this->assertEquals(7, $this->transformFile('Stubs/Xpath/Date/day-from-dateTime.xsl'));
    }

    public function testHoursFromTime(): void
    {
        $this->assertEquals(19, $this->transformFile('Stubs/Xpath/Date/hours-from-time.xsl'));
    }

    public function testHoursFromDateTime(): void
    {
        $this->assertEquals(5, $this->transformFile('Stubs/Xpath/Date/hours-from-dateTime.xsl'));
    }

    public function testMinutesFromTime(): void
    {
        $this->assertEquals(59, $this->transformFile('Stubs/Xpath/Date/minutes-from-time.xsl'));
    }

    public function testMinutesFromDateTime(): void
    {
        $this->assertEquals(8, $this->transformFile('Stubs/Xpath/Date/minutes-from-dateTime.xsl'));
    }

    public function testSecondsFromTime(): void
    {
        $this->assertEquals(20, $this->transformFile('Stubs/Xpath/Date/seconds-from-time.xsl'));
    }

    public function testSecondsFromDateTime(): void
    {
        $this->assertEquals(10, $this->transformFile('Stubs/Xpath/Date/seconds-from-dateTime.xsl'));
    }
}
