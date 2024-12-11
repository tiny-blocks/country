<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Internal;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Alpha3Code;
use TinyBlocks\Country\AlphaCode;
use TinyBlocks\Country\Internal\Exceptions\EmptyCountryName;

final class NameTest extends TestCase
{
    public function testEmptyCountryName(): void
    {
        /** @Given an empty country name */
        $this->expectException(EmptyCountryName::class);
        $this->expectExceptionMessage('Country name cannot be empty.');

        /** @When attempting to create a Name with an empty string */
        Name::from(name: '');
    }

    #[DataProvider('dataProvider')]
    public function testBuildFromAlphaCodeName(string $expected, AlphaCode $alphaCode): void
    {
        /** @Given a valid AlphaCode */
        /** @When building a Name from the given AlphaCode */
        $name = Name::fromAlphaCode(alphaCode: $alphaCode);

        /** @Then the Name value should match the expected value */
        self::assertSame($expected, $name->value);
    }

    public static function dataProvider(): array
    {
        return [
            'Alpha2 code Brazil'                                               => [
                'expected'  => 'Brazil',
                'alphaCode' => Alpha2Code::BRAZIL
            ],
            'Alpha2 code South Korea'                                          => [
                'expected'  => 'South Korea',
                'alphaCode' => Alpha2Code::SOUTH_KOREA
            ],
            'Alpha2 code United States of America'                             => [
                'expected'  => 'United States of America',
                'alphaCode' => Alpha2Code::UNITED_STATES_OF_AMERICA
            ],
            'Alpha3 code Saint Vincent and the Grenadines'                     => [
                'expected'  => 'Saint Vincent and the Grenadines',
                'alphaCode' => Alpha3Code::SAINT_VINCENT_AND_THE_GRENADINES
            ],
            'Alpha3 code South Georgia and the South Sandwich Islands'         => [
                'expected'  => 'South Georgia and the South Sandwich Islands',
                'alphaCode' => Alpha3Code::SOUTH_GEORGIA_AND_THE_SOUTH_SANDWICH_ISLANDS
            ],
            'Alpha3 code United Kingdom of Great Britain and Northern Ireland' => [
                'expected'  => 'United Kingdom of Great Britain and Northern Ireland',
                'alphaCode' => Alpha3Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND
            ]
        ];
    }
}
