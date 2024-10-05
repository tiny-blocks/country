<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class Alpha3CodeTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testValidValues(string $value): void
    {
        /** @Given a valid Alpha3 code */
        /** @Then the value should have a length of 3 characters */
        self::assertEquals(3, strlen($value));
    }

    public static function dataProvider(): array
    {
        return array_map(static fn(Alpha3Code $alpha3Code) => [
            'value' => $alpha3Code->value
        ], Alpha3Code::cases());
    }
}
