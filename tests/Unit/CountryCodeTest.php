<?php

declare(strict_types=1);

namespace Test\TinyBlocks\Country\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Alpha3Code;
use TinyBlocks\Country\CountryCode;
use TinyBlocks\Country\NumericCode;
use ValueError;

final class CountryCodeTest extends TestCase
{
    #[DataProvider('toStringDataProvider')]
    public function testToStringReturnsBackingValue(CountryCode $code, string $expected): void
    {
        /** @Given a country code in one of the three representations */

        /** @When reading its string value */
        $string = $code->toString();

        /** @Then it equals the backing value */
        self::assertSame($expected, $string);
    }

    #[DataProvider('toIntegerDataProvider')]
    public function testToIntegerDiscardsLeadingZeros(NumericCode $numeric, int $expected): void
    {
        /** @Given a numeric code */

        /** @When reading it as an integer */
        $integer = $numeric->toInteger();

        /** @Then the leading zeros are discarded */
        self::assertSame($expected, $integer);
    }

    public function testConversionsArePairwiseIdentity(): void
    {
        /** @Given a lookup of every Alpha3Code by case name */
        $alpha3ByName = array_combine(
            array_map(static fn(Alpha3Code $code): string => $code->name, Alpha3Code::cases()),
            Alpha3Code::cases()
        );

        /** @And a lookup of every NumericCode by case name */
        $numericByName = array_combine(
            array_map(static fn(NumericCode $code): string => $code->name, NumericCode::cases()),
            NumericCode::cases()
        );

        /** @When converting each code to every representation, including itself */
        foreach (Alpha2Code::cases() as $alpha2) {
            $alpha3 = $alpha3ByName[$alpha2->name];
            $numeric = $numericByName[$alpha2->name];

            /** @Then every conversion yields the matching case in every direction */
            self::assertSame($alpha2, $alpha2->toAlpha2());
            self::assertSame($alpha3, $alpha2->toAlpha3());
            self::assertSame($numeric, $alpha2->toNumeric());
            self::assertSame($alpha2, $alpha3->toAlpha2());
            self::assertSame($alpha3, $alpha3->toAlpha3());
            self::assertSame($numeric, $alpha3->toNumeric());
            self::assertSame($alpha2, $numeric->toAlpha2());
            self::assertSame($alpha3, $numeric->toAlpha3());
            self::assertSame($numeric, $numeric->toNumeric());
        }
    }

    public function testCaseNamesMatchAcrossRepresentations(): void
    {
        /** @Given the case names of every Alpha2Code */
        $alpha2Names = array_map(static fn(Alpha2Code $code): string => $code->name, Alpha2Code::cases());

        /** @And the case names of every Alpha3Code */
        $alpha3Names = array_map(static fn(Alpha3Code $code): string => $code->name, Alpha3Code::cases());

        /** @When fetching the case names of every NumericCode */
        $numericNames = array_map(static fn(NumericCode $code): string => $code->name, NumericCode::cases());

        /** @Then all three representations share the same case names in the same order */
        self::assertSame($alpha2Names, $alpha3Names);
        self::assertSame($alpha2Names, $numericNames);
    }

    public function testFromStringResolvesEachRepresentation(): void
    {
        /** @Given the Alpha-2, Alpha-3, and numeric codes for Brazil */

        /** @When creating each code from its string value */
        /** @Then each resolves to the Brazil case */
        self::assertSame(Alpha2Code::BRAZIL, Alpha2Code::fromString(code: 'BR'));
        self::assertSame(Alpha3Code::BRAZIL, Alpha3Code::fromString(code: 'BRA'));
        self::assertSame(NumericCode::BRAZIL, NumericCode::fromString(code: '076'));
    }

    public function testTryFromStringResolvesEachRepresentation(): void
    {
        /** @Given the Alpha-2, Alpha-3, and numeric codes for Brazil */

        /** @When trying to create each code from its string value */
        /** @Then each resolves to the Brazil case */
        self::assertSame(Alpha2Code::BRAZIL, Alpha2Code::tryFromString(code: 'BR'));
        self::assertSame(Alpha3Code::BRAZIL, Alpha3Code::tryFromString(code: 'BRA'));
        self::assertSame(NumericCode::BRAZIL, NumericCode::tryFromString(code: '076'));
    }

    public function testTryFromStringWhenUnknownThenReturnsNull(): void
    {
        /** @Given an unknown code in each representation */

        /** @When trying to create each code from its unknown value */
        /** @Then null is returned in every representation */
        self::assertNull(Alpha2Code::tryFromString(code: 'ZZ'));
        self::assertNull(Alpha3Code::tryFromString(code: 'ZZZ'));
        self::assertNull(NumericCode::tryFromString(code: '999'));
    }

    public function testFromStringWhenUnknownThenThrowsValueError(): void
    {
        /** @Given an unknown code in each representation */
        $factories = [
            static fn(): Alpha2Code => Alpha2Code::fromString(code: 'ZZ'),
            static fn(): Alpha3Code => Alpha3Code::fromString(code: 'ZZZ'),
            static fn(): NumericCode => NumericCode::fromString(code: '999')
        ];

        /** @When creating each code from its unknown value */
        foreach ($factories as $createFromUnknown) {
            /** @Then a ValueError is thrown */
            $thrown = false;
            try {
                $createFromUnknown();
            } catch (ValueError) {
                $thrown = true;
            }
            self::assertTrue($thrown);
        }
    }

    public static function toStringDataProvider(): array
    {
        return [
            'Alpha-2 Brazil'  => ['code' => Alpha2Code::BRAZIL, 'expected' => 'BR'],
            'Alpha-3 Brazil'  => ['code' => Alpha3Code::BRAZIL, 'expected' => 'BRA'],
            'Numeric Brazil'  => ['code' => NumericCode::BRAZIL, 'expected' => '076'],
            'Numeric Andorra' => ['code' => NumericCode::ANDORRA, 'expected' => '020']
        ];
    }

    public static function toIntegerDataProvider(): array
    {
        return [
            'Brazil leading zero'      => ['numeric' => NumericCode::BRAZIL, 'expected' => 76],
            'Afghanistan two zeros'    => ['numeric' => NumericCode::AFGHANISTAN, 'expected' => 4],
            'United States no padding' => ['numeric' => NumericCode::UNITED_STATES_OF_AMERICA, 'expected' => 840]
        ];
    }
}
