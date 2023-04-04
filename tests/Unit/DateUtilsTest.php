<?php

namespace tests\Unit;

use helpers\Helpers;
use PHPUnit\Framework\TestCase;

class DateUtilsTest extends TestCase
{
    /**
     * @dataProvider datesProvider
     */
    public function testGetDateDiff($date1, $date2, $interval, $expectedResult)
    {
        $result = Helpers::get_date_diff($date1, $date2, $interval);
        $this->assertEquals($expectedResult, $result);
    }

    public function datesProvider(): array
    {
        return [
            ['2022-01-01', '2022-01-01', 'days', '0'],
            ['2022-01-01', '2022-01-02', 'days', '1'],
            ['2022-01-01', '2022-01-01 12:00:00', 'hours', '12 hours'],
            ['2022-01-01', '2022-01-02 12:30:00', 'hours', '36 hours 30 minutes'],
            ['2022-01-01', '2022-01-08', 'weeks', '1'],
            ['2022-01-01', '2023-01-01', 'months', '12'],
            ['2022-01-01', '2024-01-01', 'years', '2'],
            ['2022-01-01', '2022-01-02', 'minutes', '1440'],
            ['2023-01-01', '2022-01-01', 'days', '365'],
            ['2023-12-31', '2024-01-01', 'days', '1'],
        ];
    }
}
