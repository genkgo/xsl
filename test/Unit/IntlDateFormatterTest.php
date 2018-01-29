<?php
namespace Genkgo\Xsl\Integration\Xsl;

use DateTime;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;
use Genkgo\Xsl\Xsl\Functions\Formatter\IntlDateTimeFormatter;

class IntlDateFormatterTest extends AbstractXslTest
{
    public function testFormatDate()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagDate();

        $this->assertEquals(
            '2015-10-16',
            $formatter->format(new DateTime('2015-10-16'), '[Y]-[M]-[D]', 'en_US', null)
        );

        $this->assertEquals(
            '42',
            $formatter->format(new DateTime('2015-10-16'), '[W]', 'en_US', null)
        );
    }

    public function testExceptionCode()
    {
        try {
            $formatter = IntlDateTimeFormatter::createWithFlagDate();
            $formatter->format(new DateTime('2015-10-16'), '[H]', 'en_US', null);
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('XTDE1340', $e->getErrorCode());
        }
    }

    public function testFormatTime()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagTime();

        $this->assertEquals(
            '09:37:00',
            $formatter->format(new DateTime('09:37:00'), '[H]:[m]:[s]', 'en_US', null)
        );

        $this->assertEquals(
            'AM',
            $formatter->format(new DateTime('09:37:00'), '[P]', 'en_US', null)
        );
    }

    public function testFormatDateTime()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagDateTime();

        $this->assertThat(
            $formatter->format(new DateTime('2015-10-16 15:37:00'), '[Y]-[M]-[D] [H]:[m]:[s] [Z]', 'en_US', null),
            $this->logicalOr(
                '2015-10-16 15:37:00 GMT',
                '2015-10-16 15:37:00 GMT+2',
                '2015-10-16 15:37:00 GMT+02:00',
                '2015-10-16 15:37:00 CEST'
            )
        );

        $this->assertThat(
            $formatter->format(new DateTime('2015-10-16 15:37:00'), '[Y]-[M]-[D] [h]:[m]:[s] [P] [z]', 'en_US', null),
            $this->logicalOr('2015-10-16 03:37:00 PM GMT+02:00', '2015-10-16 03:37:00 PM GMT')
        );

        $this->assertEquals(
            '289 Friday',
            $formatter->format(new DateTime('2015-10-16 15:37:00'), '[d] [F]', 'en_US', null)
        );
    }

    public function testInvalidPicture()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagTime();

        try {
            $this->assertEquals(
                '09:37:00',
                $formatter->format(new DateTime('2015-10-16'), '[Y]]', 'en_US', null)
            );
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('XTDE1340', $e->getErrorCode());
        }
    }

    public function testNoValidComponents()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No valid components found');

        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $formatter->format(new DateTime('2015-10-16'), '[A]', 'en_US', null);
    }

    public function testNotSupportedComponent()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Component [E] is not supported');

        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $formatter->format(new DateTime('2015-10-16'), '[E]', 'en_US', null);
    }

    public function testEscapeBrackets()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagDateTime();
        $this->assertEquals(
            '[Date:] 2015-05-16 03:37:00 PM',
            $formatter->format(
                new DateTime('2015-05-16 15:37:00'),
                '[[Date:]] [Y]-[M]-[D] [h]:[m]:[s] [P]',
                'en_US',
                null
            )
        );
    }

    public function testUnclosedFormat()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Wrong formatted date, missing ]');

        $formatter = IntlDateTimeFormatter::createWithFlagDateTime();
        $formatter->format(
            new DateTime('2015-10-16 15:37:00'),
            '[[ Hallo [Y',
            'en_US',
            null
        );
    }

    public function testFormatDateNo24Hour()
    {
        $this->expectException(InvalidArgumentException::class);

        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $formatter->format(
            new DateTime('2015-10-16'),
            '[H]',
            'en_US',
            null
        );
    }

    public function testWrongEscape()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Wrong formatted date, escape by doubling [[ and ]]');

        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $formatter->format(
            new DateTime('2015-10-16'),
            ']',
            'en_US',
            null
        );
    }

    public function testFormatMonthName()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $this->assertEquals(
            'October',
            $formatter->format(
                new DateTime('2015-10-16 15:37:00'),
                '[MNn]',
                'en_US',
                null
            )
        );
    }

    public function testFormatMonthNameAbbreviated()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $this->assertEquals(
            'Oct',
            $formatter->format(
                new DateTime('2015-10-16 15:37:00'),
                '[MNn,*-3]',
                'en_US',
                null
            )
        );
    }

    public function testFormatDayName()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $this->assertEquals(
            'Friday',
            $formatter->format(
                new DateTime('2015-10-16 15:37:00'),
                '[F]',
                'en_US',
                null
            )
        );
    }

    public function testFormatDayNameAbbreviated()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $this->assertEquals(
            'Fri',
            $formatter->format(
                new DateTime('2015-10-16 15:37:00'),
                '[FNn,*-3]',
                'en_US',
                null
            )
        );
    }

    public function testFormatDayNameFull()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $this->assertEquals(
            'Friday',
            $formatter->format(
                new DateTime('2015-10-16 15:37:00'),
                '[FNn,*-4]',
                'en_US',
                null
            )
        );
    }

    public function testUpperCase()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $this->assertEquals(
            'FRI',
            $formatter->format(
                new DateTime('2015-10-16 15:37:00'),
                '[FN,*-3]',
                'en_US',
                null
            )
        );
    }

    public function testLowerCase()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $this->assertEquals(
            'fri',
            $formatter->format(
                new DateTime('2015-10-16 15:37:00'),
                '[Fn,*-3]',
                'en_US',
                null
            )
        );
    }

    public function testFormatDayNameAbbreviatedCapitalWithMonthCapital()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $this->assertEquals(
            'OCTOBER FRI',
            $formatter->format(
                new DateTime('2015-10-16 15:37:00'),
                '[MN] [FN,*-3]',
                'en_US',
                null
            )
        );
    }

    public function testFormatDayInMonthWithoutLeadingZero()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $this->assertSame(
            '8',
            $formatter->format(
                new DateTime('2015-10-08 15:37:00'),
                '[D,*-1]',
                'en_US',
                null
            )
        );
    }

    public function testFormatMonthWithoutLeadingZero()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $this->assertSame(
            '5',
            $formatter->format(
                new DateTime('2015-05-08 15:37:00'),
                '[M,*-1]',
                'en_US',
                null
            )
        );
    }

    public function testMonthName()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $this->assertSame(
            '08 May 2015',
            $formatter->format(
                new DateTime('2015-05-08 15:37:00'),
                '[D] [MNn] [Y]',
                'en_US',
                null
            )
        );
    }

    public function testJanuaryFirst2017()
    {
        $formatter = IntlDateTimeFormatter::createWithFlagDate();
        $this->assertSame(
            '01-01-2017',
            $formatter->format(
                new \DateTime('2017-01-01 18:42:34.000000', new \DateTimeZone('UTC')),
                '[D]-[M]-[Y]',
                'nl_NL',
                null
            )
        );
    }
}
