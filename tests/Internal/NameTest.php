<?php

namespace TinyBlocks\Country\Internal;

use PHPUnit\Framework\TestCase;
use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Alpha3Code;
use TinyBlocks\Country\Internal\Exceptions\EmptyCountryName;

final class NameTest extends TestCase
{
    public function testEmptyCountryName(): void
    {
        $this->expectException(EmptyCountryName::class);
        $this->expectErrorMessage('Country name cannot be empty.');

        Name::from(name: '');
    }

    /**
     * @dataProvider providerForTestBuildFromAlphaCodeName
     */
    public function testBuildFromAlphaCodeName(string $expected, AlphaCode $alphaCode): void
    {
        $name = Name::fromAlphaCode(alphaCode: $alphaCode);

        $actual = $name->value;

        self::assertEquals($expected, $actual);
    }

    public function providerForTestBuildFromAlphaCodeName(): array
    {
        return [
            [
                'expected'  => 'Brazil',
                'alphaCode' => Alpha2Code::BRAZIL
            ],
            [
                'expected'  => 'South Korea',
                'alphaCode' => Alpha2Code::SOUTH_KOREA
            ],
            [
                'expected'  => 'United States of America',
                'alphaCode' => Alpha2Code::UNITED_STATES_OF_AMERICA
            ],
            [
                'expected'  => 'Saint Vincent and the Grenadines',
                'alphaCode' => Alpha3Code::SAINT_VINCENT_AND_THE_GRENADINES
            ],
            [
                'expected'  => 'South Georgia and the South Sandwich Islands',
                'alphaCode' => Alpha3Code::SOUTH_GEORGIA_AND_THE_SOUTH_SANDWICH_ISLANDS
            ],
            [
                'expected'  => 'United Kingdom of Great Britain and Northern Ireland',
                'alphaCode' => Alpha3Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND
            ]
        ];
    }
}
