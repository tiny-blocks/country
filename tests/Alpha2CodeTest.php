<?php

namespace TinyBlocks\Country;

use PHPUnit\Framework\TestCase;

final class Alpha2CodeTest extends TestCase
{
    /**
     * @dataProvider providerForTestValidValues
     */
    public function testValidValues(string $value): void
    {
        self::assertEquals(2, strlen($value));
    }

    public function providerForTestValidValues(): array
    {
        return array_map(fn(Alpha2Code $alpha2Code) => [
            'value' => $alpha2Code->value
        ], Alpha2Code::cases());
    }
}
