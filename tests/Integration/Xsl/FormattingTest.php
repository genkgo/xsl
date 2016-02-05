<?php
namespace Genkgo\Xsl\Integration\Xsl;

use Genkgo\Xsl\Schema\XsDate;
use Genkgo\Xsl\Schema\XsDateTime;
use Genkgo\Xsl\Schema\XsTime;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;

class FormattingTest extends AbstractXslTest
{
    public function testFormatDate()
    {
        $xsDate = XsDate::fromString('2015-10-16');

        $this->assertEquals('2015-10-16', $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
            'date' => (string) $xsDate,
            'picture' => '[Y]-[M]-[D]'
        ]));

        $this->assertEquals('42', $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
            'date' => (string) $xsDate,
            'picture' => '[W]'
        ]));
    }

    public function testExceptionCode()
    {
        try {
            $xsDate = XsDate::fromString('2015-10-16');

            $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
                'date' => (string) $xsDate,
                'picture' => '[H]'
            ]);
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('XTDE1340', $e->getErrorCode());
        }
    }

    public function testFormatTime()
    {
        $xsTime = XsTime::fromString('09:37:00');

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
        $xsDateTime = XsDateTime::fromString('2015-10-16 15:37:00');

        $this->assertEquals('2015-10-16 15:37:00 +02:00', $this->transformFile('Stubs/Xsl/Formatting/format-dateTime.xsl', [
            'dateTime' => (string) $xsDateTime,
            'picture' => '[Y]-[M]-[D] [H]:[m]:[s] [Z]'
        ]));

        $this->assertEquals('2015-10-16 03:37:00 PM GMT+02:00', $this->transformFile('Stubs/Xsl/Formatting/format-dateTime.xsl', [
            'dateTime' => (string) $xsDateTime,
            'picture' => '[Y]-[M]-[D] [h]:[m]:[s] [P] [z]'
        ]));

        $this->assertEquals('289 Friday', $this->transformFile('Stubs/Xsl/Formatting/format-dateTime.xsl', [
            'dateTime' => (string) $xsDateTime,
            'picture' => '[d] [F]'
        ]));
    }

    public function testInvalidPicture()
    {
        try {
            $xsDate = XsDate::fromString('2015-10-16');

            $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
                'date' => (string) $xsDate,
                'picture' => '[Y]]'
            ]);
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('XTDE1340', $e->getErrorCode());
        }
    }

    public function testNoValidComponents()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'No valid components found');

        $xsDate = XsDate::fromString('2015-10-16');

        $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
            'date' => (string) $xsDate,
            'picture' => '[A]'
        ]);
    }

    public function testNotSupportedComponent()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Component [E] is not supported');

        $xsDate = XsDate::fromString('2015-10-16');

        $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
            'date' => (string) $xsDate,
            'picture' => '[E]'
        ]);
    }

    public function testEscapeBrackets()
    {
        $xsDateTime = XsDateTime::fromString('2015-10-16 15:37:00');

        $this->assertEquals('[Date:] 2015-10-16 03:37:00 PM', $this->transformFile('Stubs/Xsl/Formatting/format-dateTime.xsl', [
            'dateTime' => (string) $xsDateTime,
            'picture' => '[[Date:]] [Y]-[M]-[D] [h]:[m]:[s] [P]'
        ]));
    }

    public function testUnclosedFormat()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Wrong formatted date, missing ]');

        $xsDateTime = XsDateTime::fromString('2015-10-16 15:37:00');

        $this->transformFile('Stubs/Xsl/Formatting/format-dateTime.xsl', [
            'dateTime' => (string) $xsDateTime,
            'picture' => '[[ Hallo [Y'
        ]);
    }

    public function testInvalidDataType()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Expected a date object, got scalar');

        $xsDateTime = XsDateTime::fromString('2015-10-16 15:37:00');

        $this->transformFile('Stubs/Xsl/Formatting/format-invalid-dataType.xsl', [
            'dateTime' => (string) $xsDateTime,
            'picture' => '[[ Hallo [Y'
        ]);
    }

    public function testInvalidSequence()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Expected a http://www.w3.org/2001/XMLSchema:dateTime object, got xs:item');

        $xsDateTime = XsDateTime::fromString('2015-10-16 15:37:00');

        $this->transformFile('Stubs/Xsl/Formatting/format-invalid-sequence.xsl', [
            'dateTime' => (string) $xsDateTime,
            'picture' => '[Y]'
        ]);
    }

    public function testFormatDateNo24Hour()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $xsDate = XsDate::fromString('2015-10-16');

        $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
            'date' => (string) $xsDate,
            'picture' => '[H]'
        ]);
    }

    public function testFormatDateNo12Hour()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $xsDate = XsDate::fromString('2015-10-16');

        $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
            'date' => (string) $xsDate,
            'picture' => '[h]'
        ]);
    }

    public function testFormatDateNoMinutes()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $xsDate = XsDate::fromString('2015-10-16');

        $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
            'date' => (string) $xsDate,
            'picture' => '[m]'
        ]);
    }

    public function testFormatDateNoSeconds()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $xsDate = XsDate::fromString('2015-10-16');

        $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
            'date' => (string) $xsDate,
            'picture' => '[s]'
        ]);
    }

    public function testFormatDateNoTimeMarker()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $xsDate = XsDate::fromString('2015-10-16');

        $this->transformFile('Stubs/Xsl/Formatting/format-date.xsl', [
            'date' => (string) $xsDate,
            'picture' => '[P]'
        ]);
    }

    public function testFormatTimeNoYear()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $xsTime = XsTime::fromString('09:37:00');

        $this->transformFile('Stubs/Xsl/Formatting/format-time.xsl', [
            'time' => (string) $xsTime,
            'picture' => '[Y]'
        ]);
    }

    public function testFormatTimeNoMonth()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $xsTime = XsTime::fromString('09:37:00');

        $this->transformFile('Stubs/Xsl/Formatting/format-time.xsl', [
            'time' => (string) $xsTime,
            'picture' => '[M]'
        ]);
    }

    public function testFormatTimeNoDay()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $xsTime = XsTime::fromString('09:37:00');

        $this->transformFile('Stubs/Xsl/Formatting/format-time.xsl', [
            'time' => (string) $xsTime,
            'picture' => '[D]'
        ]);
    }

    public function testFormatTimeNoDayInYear()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $xsTime = XsTime::fromString('09:37:00');

        $this->transformFile('Stubs/Xsl/Formatting/format-time.xsl', [
            'time' => (string) $xsTime,
            'picture' => '[d]'
        ]);
    }

    public function testFormatTimeNoWeek()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $xsTime = XsTime::fromString('09:37:00');

        $this->transformFile('Stubs/Xsl/Formatting/format-time.xsl', [
            'time' => (string) $xsTime,
            'picture' => '[W]'
        ]);
    }

    public function testFormatTimeNoDayOfWeek()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $xsTime = XsTime::fromString('09:37:00');

        $this->transformFile('Stubs/Xsl/Formatting/format-time.xsl', [
            'time' => (string) $xsTime,
            'picture' => '[F]'
        ]);
    }

    public function testFormatCurrent()
    {
        $this->assertEquals(
            date('d'),
            $this->transformFile('Stubs/Xsl/Formatting/format-current.xsl', [
                'picture' => '[D]'
            ])
        );
    }

    public function testFormatMonthName()
    {
        $xsDateTime = XsDateTime::fromString('2015-10-16 15:37:00');

        $this->assertEquals(
            'October',
            $this->transformFile('Stubs/Xsl/Formatting/format-dateTime.xsl', [
                'dateTime' => (string)$xsDateTime,
                'picture' => '[MNn]'
            ])
        );
    }

    public function testFormatMonthNameAbbreviated()
    {
        $xsDateTime = XsDateTime::fromString('2015-10-16 15:37:00');

        $this->assertEquals(
            'Oct',
            $this->transformFile('Stubs/Xsl/Formatting/format-dateTime.xsl', [
                'dateTime' => (string)$xsDateTime,
                'picture' => '[MNn,*-3]'
            ])
        );
    }

    public function testFormatDayName()
    {
        $xsDateTime = XsDateTime::fromString('2015-10-16 15:37:00');

        $this->assertEquals(
            'Friday',
            $this->transformFile('Stubs/Xsl/Formatting/format-dateTime.xsl', [
                'dateTime' => (string)$xsDateTime,
                'picture' => '[FNn]'
            ])
        );
    }

    public function testFormatDayNameAbbreviated()
    {
        $xsDateTime = XsDateTime::fromString('2015-10-16 15:37:00');

        $this->assertEquals(
            'Fri',
            $this->transformFile('Stubs/Xsl/Formatting/format-dateTime.xsl', [
                'dateTime' => (string)$xsDateTime,
                'picture' => '[FNn,*-3]'
            ])
        );
    }

    public function testFormatDayNameAbbreviatedCapital()
    {
        $xsDateTime = XsDateTime::fromString('2015-10-16 15:37:00');

        $this->assertEquals(
            'FRI',
            $this->transformFile('Stubs/Xsl/Formatting/format-dateTime.xsl', [
                'dateTime' => (string)$xsDateTime,
                'picture' => '[FN,*-3]'
            ])
        );
    }

    public function testFormatDayInMonthWithoutLeadingZero()
    {
        $xsDateTime = XsDateTime::fromString('2015-10-08 15:37:00');

        $this->assertEquals(
            '8',
            $this->transformFile('Stubs/Xsl/Formatting/format-dateTime.xsl', [
                'dateTime' => (string)$xsDateTime,
                'picture' => '[D,1]'
            ])
        );
    }
}
