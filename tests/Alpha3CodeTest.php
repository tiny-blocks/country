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
        self::assertSame(Alpha3Code::CODE_LENGTH, strlen($value));
    }

    #[DataProvider('dataProviderToAlpha2')]
    public function testToAlpha2(Alpha3Code $alpha3Code, Alpha2Code $expected): void
    {
        /** @When the toAlpha2 method is called */
        $alpha2Code = $alpha3Code->toAlpha2();

        /** @Then the expected Alpha-2 code should be returned */
        self::assertSame($expected, $alpha2Code);
    }

    public static function dataProvider(): array
    {
        return array_map(static fn(Alpha3Code $alpha3Code) => [
            'value' => $alpha3Code->value
        ], Alpha3Code::cases());
    }

    public static function dataProviderToAlpha2(): array
    {
        return [
            'Alpha-3 code for Japan to Alpha-2'                    => [
                'alpha3Code' => Alpha3Code::JAPAN,
                'expected'   => Alpha2Code::JAPAN
            ],
            'Alpha-3 code for Brazil to Alpha-2'                   => [
                'alpha3Code' => Alpha3Code::BRAZIL,
                'expected'   => Alpha2Code::BRAZIL
            ],
            'Alpha-3 code for Switzerland to Alpha-2'              => [
                'alpha3Code' => Alpha3Code::SWITZERLAND,
                'expected'   => Alpha2Code::SWITZERLAND
            ],
            'Alpha-3 code for United States of America to Alpha-2' => [
                'alpha3Code' => Alpha3Code::UNITED_STATES_OF_AMERICA,
                'expected'   => Alpha2Code::UNITED_STATES_OF_AMERICA
            ]
        ];
    }
}
