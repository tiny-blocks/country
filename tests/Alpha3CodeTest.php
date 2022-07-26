<?php

namespace TinyBlocks\Country;

use PHPUnit\Framework\TestCase;

final class Alpha3CodeTest extends TestCase
{
    /**
     * @dataProvider providerForTestValidValues
     */
    public function testValidValues(string $value): void
    {
        self::assertEquals(3, strlen($value));
    }

    public function providerForTestValidValues(): array
    {
        return array_map(fn(Alpha3Code $alpha3Code) => [
            'value' => $alpha3Code->value
        ], Alpha3Code::cases());
    }
}
