<?php

namespace marx\tests\time;

use marx\time\Time;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class TimeTest extends TestCase
{
    public function testnowDate()
    {
        $cases = [
            [
                'format' => Time::FMT_DATE,
                'expected' => date('Y-m-d'),
            ],
            [
                'format' => Time::FMT_DATE_HOUR,
                'expected' => date('Y-m-d H'),
            ],
            [
                'format' => Time::FMT_DATE_MINUTE,
                'expected' => date('Y-m-d H:i'),
            ],
            [
                'format' => Time::FMT_DATE_TIME,
                'expected' => date('Y-m-d H:i:s'),
            ],
            [
                'format' => Time::FMT_TIME,
                'expected' => date('H:i:s'),
            ],
            [
                'format' => '',
                'expected' => date('Y-m-d H:i:s'),
            ],
        ];
        foreach ($cases as $case) {
            $result = Time::nowDate($case['format']);
            $this->assertEquals($case['expected'], $result);
        }
    }

    public function testAddDaysDate()
    {
        $cases = [
            [
                'days' => 1,
                'date' => '2021-01-15 10:50:02',
                'format' => Time::FMT_DATE_MINUTE,
                'expected' => '2021-01-16 10:50',
            ],
            [
                'days' => -1,
                'date' => '2021-01-15 10:50:02',
                'format' => Time::FMT_DATE_MINUTE,
                'expected' => '2021-01-14 10:50',
            ],
            [
                'days' => 1,
                'date' => '2021-01-15 10:50:02',
                'format' => Time::FMT_DATE_TIME,
                'expected' => '2021-01-16 10:50:02',
            ],
            [
                'days' => 0,
                'date' => '2021-01-15 10:50:02',
                'format' => Time::FMT_DATE_TIME,
                'expected' => '2021-01-15 10:50:02',
            ],
            [
                'days' => -15,
                'date' => '2021-01-15 10:50:02',
                'format' => '',
                'expected' => '2020-12-31 10:50:02',
            ],
        ];
        foreach ($cases as $case) {
            $result = Time::addDaysDate($case['days'], $case['date'], $case['format']);
            $this->assertEquals($case['expected'], $result);
        }
    }

    public function testFormat()
    {
        $cases = [
            [
                'time' => strtotime('2021-01-15 10:50:02'),
                'format' => Time::FMT_DATE_HOUR,
                'fail_value' => '',
                'expected' => '2021-01-15 10',
            ],
            [
                'time' => strtotime('2021-01-15 10:50:02'),
                'format' => Time::FMT_DATE_TIME,
                'fail_value' => '',
                'expected' => '2021-01-15 10:50:02',
            ],
            [
                'time' => strtotime('2021-01-15 10:50:02'),
                'format' => '',
                'fail_value' => '',
                'expected' => '2021-01-15 10:50:02',
            ],
            [
                'time' => 'abc',
                'format' => '',
                'fail_value' => '2021-01-15 10:50:02',
                'expected' => '2021-01-15 10:50:02',
            ],
        ];
        foreach ($cases as $case) {
            $result = Time::format($case['time'], $case['format'], $case['fail_value']);
            $this->assertEquals($case['expected'], $result);
        }
    }

    public function testLaterThanNow()
    {
        $cases = [
            [
                'date' => date(Time::FMT_DATE_TIME, strtotime('-1 days')),
                'expected' => false,
            ],
            [
                'date' => date(Time::FMT_DATE_TIME, strtotime('+1 days')),
                'expected' => true,
            ],
        ];
        foreach ($cases as $case) {
            $result = Time::laterThanNow($case['date']);
            $this->assertEquals($case['expected'], $result, 'error date:'.$case['date']);
        }
    }

    public function testEarlierThanNow()
    {
        $cases = [
            [
                'date' => date(Time::FMT_DATE_TIME, strtotime('-1 days')),
                'expected' => true,
            ],
            [
                'date' => date(Time::FMT_DATE_TIME, strtotime('+1 days')),
                'expected' => false,
            ],
        ];
        foreach ($cases as $case) {
            $result = Time::earlierThanNow($case['date']);
            $this->assertEquals($case['expected'], $result, 'error date:'.$case['date']);
        }
    }
}
