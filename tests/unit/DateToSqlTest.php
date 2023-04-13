<?php

namespace tests\unit;

use helpers\Helpers;
use PHPUnit\Framework\TestCase;

class DateToSqlTest extends TestCase
{
    /**
     * @dataProvider provideDateToSqlData
     *
     * @throws \Exception
     */
    public function testDateToSql(string $input, string|null $expected): void
    {
        $result = Helpers::date_to_sql($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array<int, array<int, string|null>>
     */
    public function provideDateToSqlData(): array
    {
        return [
            ['01/04/2022', '2022-04-01'],
            ['2022-04-01', '2022-04-01'],
            ['01-04-2022', '2022-04-01'],
            ['2022.04.01', '2022-04-01'],
            ['01/12/2024', '2024-12-01'],
            ['2022/04/01', '2022-04-01'],
            ['invalid-date', null],
        ];
    }
}
