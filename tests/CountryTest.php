<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use TinyBlocks\Country\Internal\AlphaCode;
use TinyBlocks\Country\Internal\Exceptions\InvalidAlphaCode;
use TinyBlocks\Country\Internal\Exceptions\InvalidAlphaCodeImplementation;
use TinyBlocks\Country\Models\AlphaCodeXpto;

final class CountryTest extends TestCase
{
    #[DataProvider('fromAlphaCodeDataProvider')]
    public function testFromAlphaCode(?string $name, AlphaCode $alphaCode, array $expected): void
    {
        /** @Given a valid alpha code and country name */
        /** @When creating a Country from the alpha code and name */
        $actual = Country::from(alphaCode: $alphaCode, name: $name);

        /** @Then the Country properties should match the expected values */
        self::assertSame($expected['name'], $actual->name);
        self::assertSame($expected['alpha2Code'], $actual->alpha2);
        self::assertSame($expected['alpha3Code'], $actual->alpha3);
    }

    #[DataProvider('fromStringAlphaCodeDataProvider')]
    public function testFromStringAlphaCode(?string $name, string $alphaCode, array $expected): void
    {
        /** @Given a valid alpha code and country name */
        /** @When creating a Country from the alpha code and name */
        $actual = Country::fromString(alphaCode: $alphaCode, name: $name);

        /** @Then the Country properties should match the expected values */
        self::assertSame($expected['name'], $actual->name);
        self::assertSame($expected['alpha2Code'], $actual->alpha2);
        self::assertSame($expected['alpha3Code'], $actual->alpha3);
    }

    #[DataProvider('invalidAlphaCodesDataProvider')]
    public function testInvalidAlphaCode(string $alphaCode): void
    {
        /** @Given an invalid alpha code */
        $template = 'Alpha code <%s> is invalid.';

        /** @Then expect an InvalidAlphaCode exception */
        $this->expectException(InvalidAlphaCode::class);
        $this->expectExceptionMessage(sprintf($template, $alphaCode));

        /** @When trying to create a Country with the invalid alpha code */
        Country::fromString(alphaCode: $alphaCode);
    }

    public function testInvalidAlphaCodeImplementation(): void
    {
        /** @Given an alpha code implementation that is not supported */
        $template = 'The alpha code implementation <%s> is invalid.';
        $this->expectException(InvalidAlphaCodeImplementation::class);
        $this->expectExceptionMessage(sprintf($template, AlphaCodeXpto::class));

        /** @When trying to create a Country with an unsupported AlphaCode implementation */
        Country::from(alphaCode: AlphaCodeXpto::SWITZERLAND);
    }

    public function testIfTheAlphaCodeHaveTheSameCountries(): void
    {
        /** @Given Alpha2Code and Alpha3Code enums */
        $alpha2Codes = array_map(static fn(Alpha2Code $alpha2Code): string => $alpha2Code->name, Alpha2Code::cases());
        $alpha3Codes = array_map(static fn(Alpha3Code $alpha3Code): string => $alpha3Code->name, Alpha3Code::cases());

        /** @Then Alpha2Code and Alpha3Code should represent the same countries */
        self::assertSame($alpha2Codes, $alpha3Codes);
    }

    public static function fromAlphaCodeDataProvider(): array
    {
        return [
            'Alpha2 code US'          => [
                'name'      => 'United States',
                'alphaCode' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expected'  => [
                    'name'       => 'United States',
                    'alpha2Code' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                    'alpha3Code' => Alpha3Code::UNITED_STATES_OF_AMERICA
                ]
            ],
            'Alpha3 code USA'         => [
                'name'      => 'United States',
                'alphaCode' => Alpha3Code::UNITED_STATES_OF_AMERICA,
                'expected'  => [
                    'name'       => 'United States',
                    'alpha2Code' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                    'alpha3Code' => Alpha3Code::UNITED_STATES_OF_AMERICA
                ]
            ],
            'Alpha2 code GB'          => [
                'name'      => 'United Kingdom of Great Britain and Northern Ireland',
                'alphaCode' => Alpha2Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'expected'  => [
                    'name'       => 'United Kingdom of Great Britain and Northern Ireland',
                    'alpha2Code' => Alpha2Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                    'alpha3Code' => Alpha3Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND
                ]
            ],
            'Alpha3 code GBR'         => [
                'name'      => 'United Kingdom of Great Britain and Northern Ireland',
                'alphaCode' => Alpha3Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'expected'  => [
                    'name'       => 'United Kingdom of Great Britain and Northern Ireland',
                    'alpha2Code' => Alpha2Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                    'alpha3Code' => Alpha3Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND
                ]
            ],
            'Alpha2 with null name'   => [
                'name'      => null,
                'alphaCode' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expected'  => [
                    'name'       => 'United States of America',
                    'alpha2Code' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                    'alpha3Code' => Alpha3Code::UNITED_STATES_OF_AMERICA
                ]
            ],
            'Alpha3 with null name'   => [
                'name'      => null,
                'alphaCode' => Alpha3Code::UNITED_STATES_OF_AMERICA,
                'expected'  => [
                    'name'       => 'United States of America',
                    'alpha2Code' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                    'alpha3Code' => Alpha3Code::UNITED_STATES_OF_AMERICA
                ]
            ],
            'Alpha2 with custom name' => [
                'name'      => 'Brasil',
                'alphaCode' => Alpha2Code::BRAZIL,
                'expected'  => [
                    'name'       => 'Brasil',
                    'alpha2Code' => Alpha2Code::BRAZIL,
                    'alpha3Code' => Alpha3Code::BRAZIL
                ]
            ],
            'Alpha3 with custom name' => [
                'name'      => 'Brasil',
                'alphaCode' => Alpha3Code::BRAZIL,
                'expected'  => [
                    'name'       => 'Brasil',
                    'alpha2Code' => Alpha2Code::BRAZIL,
                    'alpha3Code' => Alpha3Code::BRAZIL
                ]
            ]
        ];
    }

    public static function fromStringAlphaCodeDataProvider(): array
    {
        return [
            'Alpha2 code US'          => [
                'name'      => 'United States',
                'alphaCode' => Alpha2Code::UNITED_STATES_OF_AMERICA->value,
                'expected'  => [
                    'name'       => 'United States',
                    'alpha2Code' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                    'alpha3Code' => Alpha3Code::UNITED_STATES_OF_AMERICA
                ]
            ],
            'Alpha3 code USA'         => [
                'name'      => 'United States',
                'alphaCode' => Alpha3Code::UNITED_STATES_OF_AMERICA->value,
                'expected'  => [
                    'name'       => 'United States',
                    'alpha2Code' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                    'alpha3Code' => Alpha3Code::UNITED_STATES_OF_AMERICA
                ]
            ],
            'Alpha2 with null name'   => [
                'name'      => null,
                'alphaCode' => Alpha2Code::UNITED_STATES_OF_AMERICA->value,
                'expected'  => [
                    'name'       => 'United States of America',
                    'alpha2Code' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                    'alpha3Code' => Alpha3Code::UNITED_STATES_OF_AMERICA
                ]
            ],
            'Alpha3 with null name'   => [
                'name'      => null,
                'alphaCode' => Alpha3Code::UNITED_STATES_OF_AMERICA->value,
                'expected'  => [
                    'name'       => 'United States of America',
                    'alpha2Code' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                    'alpha3Code' => Alpha3Code::UNITED_STATES_OF_AMERICA
                ]
            ],
            'Alpha2 with custom name' => [
                'name'      => 'Brasil',
                'alphaCode' => Alpha2Code::BRAZIL->value,
                'expected'  => [
                    'name'       => 'Brasil',
                    'alpha2Code' => Alpha2Code::BRAZIL,
                    'alpha3Code' => Alpha3Code::BRAZIL
                ]
            ],
            'Alpha3 with custom name' => [
                'name'      => 'Brasil',
                'alphaCode' => Alpha3Code::BRAZIL->value,
                'expected'  => [
                    'name'       => 'Brasil',
                    'alpha2Code' => Alpha2Code::BRAZIL,
                    'alpha3Code' => Alpha3Code::BRAZIL
                ]
            ]
        ];
    }

    public static function invalidAlphaCodesDataProvider(): array
    {
        return [
            'Invalid code X'    => ['alphaCode' => 'X'],
            'Invalid code XY'   => ['alphaCode' => 'XY'],
            'Invalid code XYZ'  => ['alphaCode' => 'XYZ'],
            'Invalid code XYZ1' => ['alphaCode' => 'XYZ1']
        ];
    }
}
