<?php

namespace tests\unit;

use helpers\Helpers;
use PHPUnit\Framework\TestCase;

class SanitizeTest extends TestCase
{
    public function testSanitizeStringWithHtmlTags(): void
    {
        $input = '<p>Test paragraph.</p><!-- Comment --> <a href="#fragment">Other text</a>';
        $expected = 'Test paragraph. Other text';
        $this->assertSame($expected, Helpers::sanitize_string($input));
    }

    public function testSanitizeStringWithSpecialChars(): void
    {
        $input = "Hello !world! I'm a string with special characters: !@#\$%^*()_+-={}[]|\;:'\",/?";
        $expected = "Hello !world! I'm a string with special characters: !@#\$%^*()_+-={}[]|\;:'\",/?";
        $this->assertSame($expected, Helpers::sanitize_string($input));
    }

    public function testSanitizeStringWithArray(): void
    {
        $input = [
            'key1' => '<p>Test paragraph.</p><!-- Comment --> <a href="#fragment">Other text</a>',
            'key2' => "Hello !world! I'm a string with special characters: !@#\$%^*()_+-={}[]|\;:'\",/?",
        ];
        $expected = [
            'key1' => 'Test paragraph. Other text',
            'key2' => "Hello !world! I'm a string with special characters: !@#\$%^*()_+-={}[]|\;:'\",/?",
        ];
        $this->assertSame($expected, Helpers::sanitize_string($input));
    }

    public function testSanitizeStringWithEmptyInput(): void
    {
        $input = '';
        $expected = '';
        $this->assertSame($expected, Helpers::sanitize_string($input));
    }
}
