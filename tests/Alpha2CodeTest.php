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
        self::assertSame(2, strlen($value));
    }

    #[DataProvider('dataProviderToAlpha3')]
    public function testToAlpha3(Alpha2Code $alpha2Code, Alpha3Code $expected): void
    {
        /** @When the toAlpha3 method is called */
        $alpha3Code = $alpha2Code->toAlpha3();

        /** @Then the expected Alpha-3 code should be returned */
        self::assertSame($expected, $alpha3Code);
    }

    public static function dataProvider(): array
    {
        return array_map(static fn(Alpha2Code $alpha2Code) => [
            'value' => $alpha2Code->value
        ], Alpha2Code::cases());
    }

    public static function dataProviderToAlpha3(): array
    {
        return [
            'Alpha-2 code for Japan to Alpha-3'                    => [
                'alpha2Code' => Alpha2Code::JAPAN,
                'expected'   => Alpha3Code::JAPAN
            ],
            'Alpha-2 code for Brazil to Alpha-3'                   => [
                'alpha2Code' => Alpha2Code::BRAZIL,
                'expected'   => Alpha3Code::BRAZIL
            ],
            'Alpha-2 code for Switzerland to Alpha-3'              => [
                'alpha2Code' => Alpha2Code::SWITZERLAND,
                'expected'   => Alpha3Code::SWITZERLAND
            ],
            'Alpha-2 code for United States of America to Alpha-3' => [
                'alpha2Code' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expected'   => Alpha3Code::UNITED_STATES_OF_AMERICA
            ]
        ];
    }
}
