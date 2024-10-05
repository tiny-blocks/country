<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class Alpha2CodeTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testValidValues(string $value): void
    {
        /** @Given a valid Alpha2 code */
        /** @Then the value should have a length of 2 characters */
        self::assertEquals(2, strlen($value));
    }

    public static function dataProvider(): array
    {
        return array_map(static fn(Alpha2Code $alpha2Code) => [
            'value' => $alpha2Code->value
        ], Alpha2Code::cases());
    }
}
