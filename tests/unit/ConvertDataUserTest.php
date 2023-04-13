<?php

namespace tests\unit;

use helpers\Helpers;
use PHPUnit\Framework\TestCase;

class ConvertDataUserTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testConvertDateUser(): void
    {
        // Test converting a date in the default timezone and locale
        $result = Helpers::convertDateUser('2022-04-01T12:00:00Z', 'fr_FR');
        $this->assertEquals('1 avril 2022', $result);

        // Test converting a date in a different timezone and locale
        $result = Helpers::convertDateUser('2022-04-01T12:00:00Z', 'ja_JP', 'Asia/Tokyo');
        $this->assertEquals('2022年4月1日', $result);

        // Test converting a date with a different format
        $result = Helpers::convertDateUser('04/01/2022 12:00:00', 'en_GB', 'Europe/London');
        $this->assertEquals('1 April 2022', $result);

        // Test converting an invalid date string
        $this->expectException(\Exception::class);
        Helpers::convertDateUser('invalid_date');
    }

    public function testConversionWithValidDate(): void
    {
        $this->assertEquals('January 1, 2023', Helpers::convertDateUser('2023-01-01', 'en_US', 'UTC'));
    }

    public function testConversionWithInvalidDate(): void
    {
        $this->expectException(\Exception::class);
        Helpers::convertDateUser('invalid-date');
    }

    public function testConversionWithDifferentTimezone(): void
    {
        $this->assertEquals('January 1, 2023', Helpers::convertDateUser('2023-01-01', 'en_US', 'America/New_York'));
    }

    public function testConversionWithDifferentLocale(): void
    {
        $this->assertEquals('1 janvier 2023', Helpers::convertDateUser('2023-01-01', 'fr_FR'));
    }

    public function testConversionWithInvalidLocale(): void
    {
        $this->expectException(\Exception::class);
        Helpers::convertDateUser('January 1, 2023', 'invalid-local');
    }

    public function testConversionWithNullLocale(): void
    {
        $this->expectException(\TypeError::class);
        /* @phpstan-ignore-next-line */
        Helpers::convertDateUser('2023-01-01', null);
    }

    public function testConversionWithEmptyStringDate(): void
    {
        $this->expectException(\Exception::class);
        $this->assertEquals('January 1, 2023', Helpers::convertDateUser('', 'en_US', ''));
    }

    public function testConversionWithLongDateString(): void
    {
        $this->assertEquals('January 1, 2023', Helpers::convertDateUser('2023-01-01', 'en_US'));
    }

    public function testConversionWithShortDateString(): void
    {
        $this->assertNotEquals('1/1/23', Helpers::convertDateUser('2023-01-01'));
    }
}
