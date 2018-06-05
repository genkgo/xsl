<?php
namespace Genkgo\Xsl\Integration\Xsl;

use DateTime;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;
use Genkgo\Xsl\Xsl\Functions\Formatter\DateTimeFormatter;

class DateFormatterTest extends AbstractXslTest
{
    public function testFormatDate()
    {
        $formatter = DateTimeFormatter::createWithFlagDate();

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
            $formatter = DateTimeFormatter::createWithFlagDate();
            $formatter->format(new DateTime('2015-10-16'), '[H]', 'en_US', null);
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('XTDE1340', $e->getErrorCode());
        }
    }

    public function testFormatTime()
    {
        $formatter = DateTimeFormatter::createWithFlagTime();

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
        $formatter = DateTimeFormatter::createWithFlagDateTime();

        $this->assertThat(
            $formatter->format(new DateTime('2015-10-16 15:37:00'), '[Y]-[M]-[D] [H]:[m]:[s] [Z]', 'en_US', null),
            $this->logicalOr(
                '2015-10-16 15:37:00 GMT+02:00',
                '2015-10-16 15:37:00 CEST',
                '2015-10-16 15:37:00 UTC'
            )
        );

        $date = new DateTime('2015-10-16 15:37:00');

        $this->assertEquals(
            $date->format('Y-m-d h:i:s A \G\M\TP'),
            $formatter->format($date, '[Y]-[M]-[D] [h]:[m]:[s] [P] [z]', 'en_US', null)
        );

        $this->assertEquals(
            ((int)$date->format('z') + 1) . $date->format(' l'),
            $formatter->format($date, '[d] [F]', 'en_US', null)
        );
    }

    public function testInvalidPicture()
    {
        $formatter = DateTimeFormatter::createWithFlagTime();

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

        $formatter = DateTimeFormatter::createWithFlagDate();
        $formatter->format(new DateTime('2015-10-16'), '[A]', 'en_US', null);
    }

    public function testNotSupportedComponent()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Component [E] is not supported');

        $formatter = DateTimeFormatter::createWithFlagDate();
        $formatter->format(new DateTime('2015-10-16'), '[E]', 'en_US', null);
    }

    public function testEscapeBrackets()
    {
        $formatter = DateTimeFormatter::createWithFlagDateTime();
        $this->assertEquals(
            '[Date:] 2015-10-16 03:37:00 PM',
            $formatter->format(
                new DateTime('2015-10-16 15:37:00'),
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

        $formatter = DateTimeFormatter::createWithFlagDateTime();
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

        $formatter = DateTimeFormatter::createWithFlagDate();
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

        $formatter = DateTimeFormatter::createWithFlagDate();
        $formatter->format(
            new DateTime('2015-10-16'),
            ']',
            'en_US',
            null
        );
    }

    public function testMonthName()
    {
        $formatter = DateTimeFormatter::createWithFlagDate();
        $this->assertEquals('16 10 2015', $formatter->format(
            new DateTime('2015-10-16'),
            '[D] [MNn] [Y]',
            'en_US',
            null
        ));
    }
}
