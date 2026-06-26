<?php

declare(strict_types=1);

namespace Test\TinyBlocks\Country\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\TinyBlocks\Country\Models\CountryWithCallingCode;
use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Alpha3Code;
use TinyBlocks\Country\Country;
use TinyBlocks\Country\CountryCode;
use TinyBlocks\Country\CountryTimezones;
use TinyBlocks\Country\Exceptions\InvalidCountryCode;
use TinyBlocks\Country\NumericCode;
use TinyBlocks\Country\Subdivision;

final class CountryTest extends TestCase
{
    #[DataProvider('isoShortNameDataProvider')]
    public function testNameReturnsIsoShortName(Alpha2Code $alpha2, string $expectedName): void
    {
        /** @Given a Country created from an Alpha-2 code */
        $country = Country::from(code: $alpha2);

        /** @When reading its name */
        $name = $country->name();

        /** @Then it is the ISO English short name */
        self::assertSame($expectedName, $name);
    }

    public function testFromStringResolvesAlpha2(): void
    {
        /** @Given a two-letter code string */
        $code = 'BR';

        /** @When creating a Country from it */
        $country = Country::fromString(code: $code);

        /** @Then it resolves to the matching country */
        self::assertSame(Alpha2Code::BRAZIL, $country->alpha2());
    }

    public function testFromStringResolvesAlpha3(): void
    {
        /** @Given a three-letter code string */
        $code = 'USA';

        /** @When creating a Country from it */
        $country = Country::fromString(code: $code);

        /** @Then it resolves to the matching country */
        self::assertSame(Alpha2Code::UNITED_STATES_OF_AMERICA, $country->alpha2());
    }

    public function testFromStringResolvesNumeric(): void
    {
        /** @Given a three-digit numeric code string */
        $code = '076';

        /** @When creating a Country from it */
        $country = Country::fromString(code: $code);

        /** @Then it resolves to the matching country */
        self::assertSame(Alpha2Code::BRAZIL, $country->alpha2());
    }

    #[DataProvider('invalidCountryCodeStringsDataProvider')]
    public function testFromStringThrowsWhenUnknown(string $code): void
    {
        /** @Given an unknown country code string */

        /** @And a template describing the expected error message */
        $template = 'Country code <%s> is invalid.';

        /** @Then an InvalidCountryCode exception is thrown */
        $this->expectException(InvalidCountryCode::class);

        /** @And the message identifies the invalid input */
        $this->expectExceptionMessage(sprintf($template, $code));

        /** @When creating a Country from the invalid code */
        Country::fromString(code: $code);
    }

    public function testTimezonesReturnsCountryTimezones(): void
    {
        /** @Given a Country with timezones */
        $country = Country::from(code: Alpha2Code::BRAZIL);

        /** @When reading its timezones */
        $timezones = $country->timezones();

        /** @Then it is a CountryTimezones instance with entries */
        self::assertInstanceOf(CountryTimezones::class, $timezones);
        self::assertGreaterThan(0, $timezones->count());
    }

    public function testSubdivisionsReturnsThemWhenPresent(): void
    {
        /** @Given a Country with subdivisions */
        $country = Country::from(code: Alpha2Code::BRAZIL);

        /** @When reading its subdivisions */
        $subdivisions = $country->subdivisions();

        /** @Then the collection is not empty */
        self::assertGreaterThan(0, $subdivisions->count());

        /** @And every entry is a Subdivision instance */
        self::assertInstanceOf(Subdivision::class, $subdivisions->first());
    }

    #[DataProvider('countryCodeObjectsDataProvider')]
    public function testFromCodeObjectCanonicalizesToAlpha2(
        CountryCode $code,
        string $expectedName,
        Alpha2Code $expectedAlpha2,
        Alpha3Code $expectedAlpha3,
        NumericCode $expectedNumeric
    ): void {
        /** @Given a country code in one of the three representations */

        /** @When creating a Country from it */
        $country = Country::from(code: $code);

        /** @Then the name is the ISO English short name */
        self::assertSame($expectedName, $country->name());

        /** @And it exposes the matching Alpha-2 code */
        self::assertSame($expectedAlpha2, $country->alpha2());

        /** @And it exposes the matching Alpha-3 code */
        self::assertSame($expectedAlpha3, $country->alpha3());

        /** @And it exposes the matching numeric code */
        self::assertSame($expectedNumeric, $country->numeric());
    }

    #[DataProvider('invalidCountryCodeStringsDataProvider')]
    public function testTryFromStringReturnsNullWhenUnknown(string $code): void
    {
        /** @Given an unknown country code string */

        /** @When trying to create a Country from it */
        $country = Country::tryFromString(code: $code);

        /** @Then null is returned */
        self::assertNull($country);
    }

    #[DataProvider('validCountryCodeStringsDataProvider')]
    public function testTryFromStringReturnsCountryWhenValid(string $code, Alpha2Code $expectedAlpha2): void
    {
        /** @Given a valid country code string */

        /** @When trying to create a Country from it */
        $country = Country::tryFromString(code: $code);

        /** @Then the resolved Country exposes the matching Alpha-2 code */
        self::assertSame($expectedAlpha2, $country?->alpha2());
    }

    public function testSubdivisionsAreEmptyWhenCountryHasNone(): void
    {
        /** @Given a Country with no subdivisions */
        $country = Country::from(code: Alpha2Code::BOUVET_ISLAND);

        /** @When reading its subdivisions */
        $subdivisions = $country->subdivisions();

        /** @Then the collection is empty */
        self::assertTrue($subdivisions->isEmpty());
    }

    public function testEqualsWhenSameCodeThenCountriesAreEqual(): void
    {
        /** @Given a Country created from a string code */
        $country = Country::fromString(code: 'BR');

        /** @And a Country created from the equivalent code object */
        $other = Country::from(code: Alpha2Code::BRAZIL);

        /** @When comparing them */
        $areEqual = $country->equals(other: $other);

        /** @Then they are equal */
        self::assertTrue($areEqual);
    }

    public function testFromReturnsSubclassInstanceWhenExtended(): void
    {
        /** @Given an extended Country subclass that adds behavior */

        /** @When creating an instance through the inherited factory */
        $country = CountryWithCallingCode::from(code: Alpha2Code::BRAZIL);

        /** @Then the inherited country data is preserved */
        self::assertSame('Brazil', $country->name());

        /** @And the added behavior is available on the subclass instance */
        self::assertSame('+55', $country->callingCode());
    }

    public function testEqualsWhenDifferentCodeThenCountriesDiffer(): void
    {
        /** @Given a Country for Brazil */
        $country = Country::fromString(code: 'BR');

        /** @And a Country for the United States */
        $other = Country::fromString(code: 'US');

        /** @When comparing them */
        $areEqual = $country->equals(other: $other);

        /** @Then they are not equal */
        self::assertFalse($areEqual);
    }

    public static function isoShortNameDataProvider(): array
    {
        return [
            'Brazil'         => ['alpha2' => Alpha2Code::BRAZIL, 'expectedName' => 'Brazil'],
            'Andorra'        => ['alpha2' => Alpha2Code::ANDORRA, 'expectedName' => 'Andorra'],
            'United Kingdom' => [
                'alpha2'       => Alpha2Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'expectedName' => 'United Kingdom'
            ],
            'United States'  => ['alpha2' => Alpha2Code::UNITED_STATES_OF_AMERICA, 'expectedName' => 'United States'],
            'South Korea'    => ['alpha2' => Alpha2Code::SOUTH_KOREA, 'expectedName' => 'Korea, Republic of']
        ];
    }

    public static function countryCodeObjectsDataProvider(): array
    {
        return [
            'Alpha-2 Brazil'        => [
                'code'            => Alpha2Code::BRAZIL,
                'expectedName'    => 'Brazil',
                'expectedAlpha2'  => Alpha2Code::BRAZIL,
                'expectedAlpha3'  => Alpha3Code::BRAZIL,
                'expectedNumeric' => NumericCode::BRAZIL
            ],
            'Alpha-3 United States' => [
                'code'            => Alpha3Code::UNITED_STATES_OF_AMERICA,
                'expectedName'    => 'United States',
                'expectedAlpha2'  => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expectedAlpha3'  => Alpha3Code::UNITED_STATES_OF_AMERICA,
                'expectedNumeric' => NumericCode::UNITED_STATES_OF_AMERICA
            ],
            'Numeric Brazil'        => [
                'code'            => NumericCode::BRAZIL,
                'expectedName'    => 'Brazil',
                'expectedAlpha2'  => Alpha2Code::BRAZIL,
                'expectedAlpha3'  => Alpha3Code::BRAZIL,
                'expectedNumeric' => NumericCode::BRAZIL
            ]
        ];
    }

    public static function validCountryCodeStringsDataProvider(): array
    {
        return [
            'Alpha-2 BR'  => ['code' => 'BR', 'expectedAlpha2' => Alpha2Code::BRAZIL],
            'Alpha-3 USA' => ['code' => 'USA', 'expectedAlpha2' => Alpha2Code::UNITED_STATES_OF_AMERICA],
            'Numeric 076' => ['code' => '076', 'expectedAlpha2' => Alpha2Code::BRAZIL]
        ];
    }

    public static function invalidCountryCodeStringsDataProvider(): array
    {
        return [
            'Single character'        => ['code' => 'X'],
            'Two characters'          => ['code' => 'XY'],
            'Three characters'        => ['code' => 'XYZ'],
            'Four characters'         => ['code' => 'XYZ1'],
            'Three digits unassigned' => ['code' => '999'],
            'Mixed alphanumeric'      => ['code' => '12A']
        ];
    }
}
