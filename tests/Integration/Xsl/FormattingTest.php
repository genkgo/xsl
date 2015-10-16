<?php
namespace Genkgo\Xsl\Integration\Xsl;

use DateTimeImmutable;
use Genkgo\Xsl\Schema\XsDate;
use Genkgo\Xsl\Schema\XsDateTime;
use Genkgo\Xsl\Schema\XsTime;

class FormattingTest extends AbstractXslTest
{
    public function testFormatDate()
    {
        $phpDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2015-10-16 09:37:00');
        $xsDate = new XsDate($phpDate);

        $this->assertEquals('2015-10-16', $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
            'date' => (string) $xsDate,
            'picture' => '[Y]-[M]-[D]'
        ]));

        $this->assertEquals($phpDate->format('W'), $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
            'date' => (string) $xsDate,
            'picture' => '[W]'
        ]));
    }

    public function testFormatTime()
    {
        $phpDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2015-10-16 09:37:00');
        $xsTime = new XsTime($phpDate);

        $this->assertEquals('09:37:00', $this->transformFile('Stubs/Xsl/Formatting/format-time.xsl', [
            'time' => (string) $xsTime,
            'picture' => '[H]:[m]:[s]'
        ]));

        $this->assertEquals('AM', $this->transformFile('Stubs/Xsl/Formatting/format-time.xsl', [
            'time' => (string) $xsTime,
            'picture' => '[P]'
        ]));
    }

    public function testFormatDateTime()
    {
        $phpDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2015-10-16 15:37:00');
        $xsDateTime = new XsDateTime($phpDate);

        $this->assertEquals('2015-10-16 15:37:00', $this->transformFile('Stubs/Xsl/Formatting/format-dateTime.xsl', [
            'dateTime' => (string) $xsDateTime,
            'picture' => '[Y]-[M]-[D] [H]:[m]:[s]'
        ]));

        $this->assertEquals('2015-10-16 03:37:00 PM', $this->transformFile('Stubs/Xsl/Formatting/format-dateTime.xsl', [
            'dateTime' => (string) $xsDateTime,
            'picture' => '[Y]-[M]-[D] [h]:[m]:[s] [P]'
        ]));
    }
}
