<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Unit;

use DateTime;
use Genkgo\Xsl\Integration\Xsl\AbstractXslTest;
use Genkgo\Xsl\Xsl\Functions\Formatter\IntlDateTimeFormatter;

final class IntlDateFormatterTest extends AbstractXslTest
{
    public function testFormatDate(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());

        $this->assertEquals(
            '2015-10-16',
            $formatter->formatDate(new DateTime('2015-10-16'), '[Y]-[M]-[D]', 'en_US')
        );

        $this->assertEquals(
            '42',
            $formatter->formatDate(new DateTime('2015-10-16'), '[W]', 'en_US')
        );
    }

    public function testFormatTime(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());

        $this->assertEquals(
            '09:37:00',
            $formatter->formatTime(new DateTime('09:37:00'), '[H]:[m]:[s]', 'en_US')
        );

        $this->assertEquals(
            'AM',
            $formatter->formatTime(new DateTime('09:37:00'), '[P]', 'en_US')
        );
    }

    public function testFormatDateTime(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());

        $this->assertThat(
            $formatter->formatDateTime(new DateTime('2015-10-16 15:37:00'), '[Y]-[M]-[D] [H]:[m]:[s] [Z]', 'en_US'),
            $this->logicalOr(
                '2015-10-16 15:37:00 UTC',
                '2015-10-16 15:37:00 GMT',
                '2015-10-16 15:37:00 GMT+2',
                '2015-10-16 15:37:00 GMT+02:00',
                '2015-10-16 15:37:00 CEST'
            )
        );

        $this->assertThat(
            $formatter->formatDateTime(new DateTime('2015-10-16 15:37:00'), '[Y]-[M]-[D] [h]:[m]:[s] [P] [z]', 'en_US'),
            $this->logicalOr('2015-10-16 03:37:00 PM GMT+02:00', '2015-10-16 03:37:00 PM GMT')
        );

        $this->assertEquals(
            '289 Friday',
            $formatter->formatDateTime(new DateTime('2015-10-16 15:37:00'), '[d] [F]', 'en_US')
        );
    }

    public function testInvalidPicture(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());

        try {
            $this->assertEquals(
                '09:37:00',
                $formatter->formatTime(new DateTime('2015-10-16'), '[Y]]', 'en_US')
            );
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals(1340, $e->getCode());
        }
    }

    public function testNoValidComponents(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No valid components found');

        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $formatter->formatDate(new DateTime('2015-10-16'), '[A]', 'en_US');
    }

    public function testNotSupportedComponent(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Component [E] is not supported');

        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $formatter->formatDate(new DateTime('2015-10-16'), '[E]', 'en_US');
    }

    public function testEscapeBrackets(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $this->assertEquals(
            '[Date:] 2015-05-16 03:37:00 PM',
            $formatter->formatDateTime(
                new DateTime('2015-05-16 15:37:00'),
                '[[Date:]] [Y]-[M]-[D] [h]:[m]:[s] [P]',
                'en_US'
            )
        );
    }

    public function testUnclosedFormat(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Wrong formatted date, missing ]');

        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $formatter->formatDateTime(
            new DateTime('2015-10-16 15:37:00'),
            '[[ Hallo [Y',
            'en_US'
        );
    }

    public function testFormatDateNo24Hour(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $formatter->formatDate(
            new DateTime('2015-10-16'),
            '[H]',
            'en_US'
        );
    }

    public function testWrongEscape(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Wrong formatted date, escape by doubling [[ and ]]');

        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $formatter->formatDate(
            new DateTime('2015-10-16'),
            ']',
            'en_US'
        );
    }

    public function testFormatMonthName(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $this->assertEquals(
            'October',
            $formatter->formatDate(
                new DateTime('2015-10-16 15:37:00'),
                '[MNn]',
                'en_US'
            )
        );
    }

    public function testFormatMonthNameAbbreviated(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $this->assertEquals(
            'Oct',
            $formatter->formatDate(
                new DateTime('2015-10-16 15:37:00'),
                '[MNn,*-3]',
                'en_US'
            )
        );
    }

    public function testFormatDayName(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $this->assertEquals(
            'Friday',
            $formatter->formatDate(
                new DateTime('2015-10-16 15:37:00'),
                '[F]',
                'en_US'
            )
        );
    }

    public function testFormatDayNameAbbreviated(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $this->assertEquals(
            'Fri',
            $formatter->formatDate(
                new DateTime('2015-10-16 15:37:00'),
                '[FNn,*-3]',
                'en_US'
            )
        );
    }

    public function testFormatDayNameFull(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $this->assertEquals(
            'Friday',
            $formatter->formatDate(
                new DateTime('2015-10-16 15:37:00'),
                '[FNn,*-4]',
                'en_US'
            )
        );
    }

    public function testUpperCase(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $this->assertEquals(
            'FRI',
            $formatter->formatDate(
                new DateTime('2015-10-16 15:37:00'),
                '[FN,*-3]',
                'en_US'
            )
        );
    }

    public function testLowerCase(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $this->assertEquals(
            'fri',
            $formatter->formatDate(
                new DateTime('2015-10-16 15:37:00'),
                '[Fn,*-3]',
                'en_US'
            )
        );
    }

    public function testFormatDayNameAbbreviatedCapitalWithMonthCapital(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $this->assertEquals(
            'OCTOBER FRI',
            $formatter->formatDate(
                new DateTime('2015-10-16 15:37:00'),
                '[MN] [FN,*-3]',
                'en_US'
            )
        );
    }

    public function testFormatDayInMonthWithoutLeadingZero(): void
    {
        $formatter =new IntlDateTimeFormatter(\date_default_timezone_get());
        $this->assertSame(
            '8',
            $formatter->formatDate(
                new DateTime('2015-10-08 15:37:00'),
                '[D,*-1]',
                'en_US'
            )
        );
    }

    public function testFormatMonthWithoutLeadingZero(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $this->assertSame(
            '5',
            $formatter->formatDate(
                new DateTime('2015-05-08 15:37:00'),
                '[M,*-1]',
                'en_US'
            )
        );
    }

    public function testMonthName(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $this->assertSame(
            '08 May 2015',
            $formatter->formatDate(
                new DateTime('2015-05-08 15:37:00'),
                '[D] [MNn] [Y]',
                'en_US'
            )
        );
    }

    public function testJanuaryFirst2017(): void
    {
        $formatter = new IntlDateTimeFormatter(\date_default_timezone_get());
        $this->assertSame(
            '01-01-2017',
            $formatter->formatDate(
                new \DateTime('2017-01-01 18:42:34.000000', new \DateTimeZone('UTC')),
                '[D]-[M]-[Y]',
                'nl_NL'
            )
        );
    }
}
