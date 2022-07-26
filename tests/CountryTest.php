<?php

namespace TinyBlocks\Country;

use PHPUnit\Framework\TestCase;
use TinyBlocks\Country\Internal\Exceptions\InvalidAlphaCode;
use TinyBlocks\Country\Internal\Exceptions\InvalidAlphaCodeImplementation;
use TinyBlocks\Country\Mock\AlphaCodeMock;

final class CountryTest extends TestCase
{
    /**
     * @dataProvider providerForTestFromAlphaCode
     */
    public function testFromAlphaCode(mixed $name, mixed $alphaCode, array $expected): void
    {
        $actual = Country::from(alphaCode: $alphaCode, name: $name);

        self::assertEquals($expected['name'], $actual->name);
        self::assertEquals($expected['alpha2Code'], $actual->alpha2);
        self::assertEquals($expected['alpha3Code'], $actual->alpha3);
    }

    /**
     * @dataProvider providerForTestInvalidAlphaCode
     */
    public function testInvalidAlphaCode(string $alphaCode): void
    {
        $template = 'Alpha code <%s> is invalid.';

        $this->expectException(InvalidAlphaCode::class);
        $this->expectErrorMessage(sprintf($template, $alphaCode));

        Country::from(alphaCode: $alphaCode);
    }

    public function testInvalidAlphaCodeImplementation(): void
    {
        $this->expectException(InvalidAlphaCodeImplementation::class);
        $this->expectErrorMessage('The alpha code implementation <TinyBlocks\Country\Mock\AlphaCodeMock> is invalid.');

        Country::from(alphaCode: AlphaCodeMock::SWITZERLAND);
    }

    public function testIfTheAlphaCodeHaveTheSameCountries(): void
    {
        $alpha2Codes = array_map(fn(Alpha2Code $alpha2Code) => $alpha2Code->name, Alpha2Code::cases());
        $alpha3Codes = array_map(fn(Alpha3Code $alpha3Code) => $alpha3Code->name, Alpha3Code::cases());

        self::assertEquals($alpha2Codes, $alpha3Codes);
    }

    public function providerForTestFromAlphaCode(): array
    {
        return [
            [
                'name'      => null,
                'alphaCode' => Alpha2Code::UNITED_STATES_OF_AMERICA->value,
                'expected'  => [
                    'name'       => 'United States of America',
                    'alpha2Code' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                    'alpha3Code' => Alpha3Code::UNITED_STATES_OF_AMERICA
                ]
            ],
            [
                'name'      => 'United States',
                'alphaCode' => Alpha2Code::UNITED_STATES_OF_AMERICA->value,
                'expected'  => [
                    'name'       => 'United States',
                    'alpha2Code' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                    'alpha3Code' => Alpha3Code::UNITED_STATES_OF_AMERICA
                ]
            ],
            [
                'name'      => 'United States',
                'alphaCode' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expected'  => [
                    'name'       => 'United States',
                    'alpha2Code' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                    'alpha3Code' => Alpha3Code::UNITED_STATES_OF_AMERICA
                ]
            ],
            [
                'name'      => null,
                'alphaCode' => Alpha3Code::UNITED_STATES_OF_AMERICA->value,
                'expected'  => [
                    'name'       => 'United States of America',
                    'alpha2Code' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                    'alpha3Code' => Alpha3Code::UNITED_STATES_OF_AMERICA
                ]
            ],
            [
                'name'      => 'United States',
                'alphaCode' => Alpha3Code::UNITED_STATES_OF_AMERICA->value,
                'expected'  => [
                    'name'       => 'United States',
                    'alpha2Code' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                    'alpha3Code' => Alpha3Code::UNITED_STATES_OF_AMERICA
                ]
            ],
            [
                'name'      => 'United States',
                'alphaCode' => Alpha3Code::UNITED_STATES_OF_AMERICA,
                'expected'  => [
                    'name'       => 'United States',
                    'alpha2Code' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                    'alpha3Code' => Alpha3Code::UNITED_STATES_OF_AMERICA
                ]
            ]
        ];
    }

    public function providerForTestInvalidAlphaCode(): array
    {
        return [
            [
                'alphaCode' => 'X'
            ],
            [
                'alphaCode' => 'XY'
            ],
            [
                'alphaCode' => 'XYZ'
            ],
            [
                'alphaCode' => 'XYZ1'
            ]
        ];
    }
}
