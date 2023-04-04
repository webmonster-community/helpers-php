<?php

namespace tests\Unit;

use helpers\Helpers;
use PHPUnit\Framework\TestCase;

class SlugTest extends TestCase
{
    public function slugDataProvider(): array
    {
        return [
            ['This is a Test', 'this-is-a-test'],
            ['This is another Test', 'this-is-another-test'],
            ['One more Test', 'one-more-test'],
            ['Testing 123', 'testing-123'],
            ['Test Slug', 'test-slug'],
            ['Slug Test', 'slug-test'],
            ['Test 1', 'test-1'],
            ['Test 2', 'test-2'],
            ['Test 3', 'test-3'],
            ['Test 4', 'test-4'],
        ];
    }

    /**
     * @dataProvider slugDataProvider
     */
    public function testCreateSlug(string $input, string $expected): void
    {
        $this->assertSame($expected, Helpers::create_slug($input));
    }
}
