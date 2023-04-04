<?php

namespace tests\Unit;

use helpers\Helpers;
use PHPUnit\Framework\TestCase;

class DateToSqlTest extends TestCase
{
    /**
     * @dataProvider provideDateToSqlData
     */
    public function testDateToSql($input, $expected)
    {
        $result = Helpers::date_to_sql($input);
        $this->assertEquals($expected, $result);
    }

    public function provideDateToSqlData(): array
    {
        return [
            ['01/04/2022', '2022-04-01'],
            ['2022-04-01', '2022-04-01'],
            ['01-04-2022', '2022-04-01'],
            ['2022.04.01', '2022-04-01'],
            ['01/04/2022', '2022-04-01'],
            ['2022/04/01', '2022-04-01'],
            ['invalid-date', null],
        ];
    }
}
