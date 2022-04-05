<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xsl;

use Genkgo\Xsl\Exception\TransformationException;
use Genkgo\Xsl\Schema\XsDate;
use Genkgo\Xsl\Schema\XsDateTime;
use Genkgo\Xsl\Schema\XsTime;

final class FormattingTest extends AbstractXslTest
{
    public function testDateTime(): void
    {
        $xsDateTime = XsDateTime::fromString('2015-10-16 15:37:00');

        $this->assertEquals(
            '2015-10-16 15:37:00',
            $this->transformFile('Stubs/Xsl/Formatting/format-dateTime.xsl', [
                'dateTime' => (string) $xsDateTime,
                'picture' => '[Y]-[M]-[D] [H]:[m]:[s]'
            ])
        );
    }

    public function testDateTimeOtherLanguage(): void
    {
        $xsDateTime = XsDateTime::fromString('2015-10-16 15:37:00');

        $this->assertEquals(
            'Friday',
            $this->transformFile('Stubs/Xsl/Formatting/format-dateTime.xsl', [
                'dateTime' => (string) $xsDateTime,
                'picture' => '[F]',
                'language' => 'en'
            ])
        );
    }

    public function testTime(): void
    {
        $xsTime = XsTime::fromString('15:37:00');

        $this->assertEquals(
            '15:37:00',
            $this->transformFile('Stubs/Xsl/Formatting/format-time.xsl', [
                'time' => (string) $xsTime,
                'picture' => '[H]:[m]:[s]'
            ])
        );
    }

    public function testTimeLanguage(): void
    {
        $xsTime = XsTime::fromString('15:37:00');

        $this->assertEquals(
            '15:37:00',
            $this->transformFile('Stubs/Xsl/Formatting/format-time.xsl', [
                'time' => (string) $xsTime,
                'picture' => '[H]:[m]:[s]',
                'language' => 'en'
            ])
        );
    }

    public function testDate(): void
    {
        $xsDate = XsDate::fromString('2015-10-16');

        $this->assertEquals(
            '2015-10-16',
            $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
                'date' => (string) $xsDate,
                'picture' => '[Y]-[M]-[D]'
            ])
        );
    }

    public function testDateOtherLanguage(): void
    {
        $xsDate = XsDate::fromString('2015-10-16');

        $this->assertEquals(
            'Friday',
            $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
                'date' => (string) $xsDate,
                'picture' => '[F]',
                'language' => 'en'
            ])
        );
    }

    public function testJanuaryFirst2017(): void
    {
        $xsDate = XsDate::fromString('2017-01-01');

        $this->assertEquals(
            '01-01-2017',
            $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
                'date' => (string) $xsDate,
                'picture' => '[D]-[M]-[Y]',
                'language' => 'nl'
            ])
        );
    }

    public function testInvalidDataType(): void
    {
        $this->expectException(TransformationException::class);
        $this->expectExceptionMessage('Expected a date object, got scalar');

        $xsDateTime = XsDateTime::fromString('2015-10-16 15:37:00');

        $this->transformFile('Stubs/Xsl/Formatting/format-invalid-dataType.xsl', [
            'dateTime' => (string) $xsDateTime,
            'picture' => '[[ Hallo [Y'
        ]);
    }

    public function testInvalidSequence(): void
    {
        $this->expectException(TransformationException::class);
        $this->expectExceptionMessage('Expected a http://www.w3.org/2001/XMLSchema:dateTime object, got xs:item');

        $xsDateTime = XsDateTime::fromString('2015-10-16 15:37:00');

        $this->transformFile('Stubs/Xsl/Formatting/format-invalid-sequence.xsl', [
            'dateTime' => (string) $xsDateTime,
            'picture' => '[Y]'
        ]);
    }

    public function testFormatCurrent(): void
    {
        $this->assertEquals(
            \date('d'),
            $this->transformFile('Stubs/Xsl/Formatting/format-current.xsl', [
                'picture' => '[D]'
            ])
        );
    }

    public function testEmptySequence(): void
    {
        $this->assertEquals(
            '',
            $this->transformFile('Stubs/Xsl/Formatting/format-empty-sequence.xsl', [
                'picture' => '[D]'
            ])
        );
    }
}
