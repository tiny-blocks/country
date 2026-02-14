<?php

declare(strict_types=1);

namespace Test\TinyBlocks\Country;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\TinyBlocks\Country\Models\AlphaCodeXpto;
use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Alpha3Code;
use TinyBlocks\Country\AlphaCode;
use TinyBlocks\Country\Country;
use TinyBlocks\Country\Internal\Exceptions\InvalidAlphaCode;
use TinyBlocks\Country\Internal\Exceptions\InvalidAlphaCodeImplementation;
use TinyBlocks\Country\Internal\Exceptions\InvalidTimezone;
use TinyBlocks\Country\Timezone;

final class CountryTest extends TestCase
{
    #[DataProvider('alphaCodeObjectsDataProvider')]
    public function testCountryFromAlphaCodeObject(
        AlphaCode $alphaCode,
        ?string $name,
        string $expectedName,
        Alpha2Code $expectedAlpha2,
        Alpha3Code $expectedAlpha3
    ): void {
        /** @Given a valid AlphaCode object */
        /** @When creating a Country from the alpha code */
        $country = Country::from(alphaCode: $alphaCode, name: $name);

        /** @Then the country name should match the expected value */
        self::assertSame($expectedName, $country->name);

        /** @And the alpha-2 code should match */
        self::assertSame($expectedAlpha2, $country->alpha2);

        /** @And the alpha-3 code should match */
        self::assertSame($expectedAlpha3, $country->alpha3);
    }

    #[DataProvider('alphaCodeStringsDataProvider')]
    public function testCountryFromAlphaCodeString(
        string $alphaCode,
        ?string $name,
        string $expectedName,
        Alpha2Code $expectedAlpha2,
        Alpha3Code $expectedAlpha3
    ): void {
        /** @Given a valid alpha code as string */
        /** @When creating a Country from the string */
        $country = Country::fromString(alphaCode: $alphaCode, name: $name);

        /** @Then the country name should match the expected value */
        self::assertSame($expectedName, $country->name);

        /** @And the alpha-2 code should match */
        self::assertSame($expectedAlpha2, $country->alpha2);

        /** @And the alpha-3 code should match */
        self::assertSame($expectedAlpha3, $country->alpha3);
    }

    public function testCountryNameIsDerivedFromAlphaCodeWhenNotProvided(): void
    {
        /** @Given an Alpha2Code without a custom name */
        $alphaCode = Alpha2Code::UNITED_STATES_OF_AMERICA;

        /** @When creating a Country without specifying a name */
        $country = Country::from(alphaCode: $alphaCode);

        /** @Then the name should be derived from the enum case in human-readable form */
        self::assertSame('United States of America', $country->name);
    }

    public function testCountryNamePreservesCustomNameWhenProvided(): void
    {
        /** @Given an Alpha3Code with a custom name */
        $alphaCode = Alpha3Code::BRAZIL;

        /** @When creating a Country with a custom name */
        $country = Country::from(alphaCode: $alphaCode, name: 'Brasil');

        /** @Then the custom name should be preserved */
        self::assertSame('Brasil', $country->name);
    }

    public function testCountryNameNormalizesPrepositionsToLowercase(): void
    {
        /** @Given an Alpha2Code whose enum name contains prepositions (of, and, the) */
        $alphaCode = Alpha2Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND;

        /** @When creating a Country without a custom name */
        $country = Country::from(alphaCode: $alphaCode);

        /** @Then prepositions should be lowercased in the derived name */
        self::assertSame('United Kingdom of Great Britain and Northern Ireland', $country->name);
    }

    public function testCountryAlpha2ConvertsToAlpha3Correctly(): void
    {
        /** @Given a Country created from an Alpha-2 code */
        $country = Country::from(alphaCode: Alpha2Code::JAPAN);

        /** @Then the alpha-3 code should correspond to the same country */
        self::assertSame(Alpha3Code::JAPAN, $country->alpha3);

        /** @And the alpha-2 code should remain the original */
        self::assertSame(Alpha2Code::JAPAN, $country->alpha2);
    }

    public function testCountryAlpha3ConvertsToAlpha2Correctly(): void
    {
        /** @Given a Country created from an Alpha-3 code */
        $country = Country::from(alphaCode: Alpha3Code::SWITZERLAND);

        /** @Then the alpha-2 code should correspond to the same country */
        self::assertSame(Alpha2Code::SWITZERLAND, $country->alpha2);

        /** @And the alpha-3 code should remain the original */
        self::assertSame(Alpha3Code::SWITZERLAND, $country->alpha3);
    }

    public function testCountryFromStringWithAlpha2Code(): void
    {
        /** @Given a valid two-letter alpha code string */
        /** @When creating a Country from the string */
        $country = Country::fromString(alphaCode: 'BR');

        /** @Then it should resolve to the correct alpha codes */
        self::assertSame(Alpha2Code::BRAZIL, $country->alpha2);
        self::assertSame(Alpha3Code::BRAZIL, $country->alpha3);
    }

    public function testCountryFromStringWithAlpha3Code(): void
    {
        /** @Given a valid three-letter alpha code string */
        /** @When creating a Country from the string */
        $country = Country::fromString(alphaCode: 'USA');

        /** @Then it should resolve to the correct alpha codes */
        self::assertSame(Alpha2Code::UNITED_STATES_OF_AMERICA, $country->alpha2);
        self::assertSame(Alpha3Code::UNITED_STATES_OF_AMERICA, $country->alpha3);
    }

    public function testCountryAlpha2AndAlpha3RepresentSameCountries(): void
    {
        /** @Given the Alpha2Code and Alpha3Code enums */
        $alpha2Names = array_map(
            static fn(Alpha2Code $code): string => $code->name,
            Alpha2Code::cases()
        );

        $alpha3Names = array_map(
            static fn(Alpha3Code $code): string => $code->name,
            Alpha3Code::cases()
        );

        /** @Then both enums should have exactly the same country names in the same order */
        self::assertSame($alpha2Names, $alpha3Names);
    }

    public function testCountryHasTimezones(): void
    {
        /** @Given a Country with known timezones */
        $country = Country::from(alphaCode: Alpha2Code::BRAZIL);

        /** @Then the timezones collection should not be empty */
        self::assertGreaterThan(0, $country->timezones->count());

        /** @And all items should be Timezone instances */
        foreach ($country->timezones->all() as $timezone) {
            self::assertInstanceOf(Timezone::class, $timezone);
        }
    }

    public function testCountryTimezonesDefaultReturnsFirstTimezone(): void
    {
        /** @Given a Country created from Alpha-2 code BR */
        $country = Country::from(alphaCode: Alpha2Code::BRAZIL);

        /** @When retrieving the default timezone */
        $default = $country->timezones->default();

        /** @Then it should be the first timezone in the collection */
        self::assertSame($country->timezones->all()[0]->value, $default->value);
    }

    public function testCountryTimezonesDefaultFallsBackToUtc(): void
    {
        /** @Given a Country whose alpha-2 code yields no IANA timezones (e.g. Bouvet Island) */
        $country = Country::from(alphaCode: Alpha2Code::BOUVET_ISLAND);

        /** @When retrieving the default timezone */
        $default = $country->timezones->default();

        /** @Then it should fall back to UTC */
        self::assertSame('UTC', $default->value);
    }

    public function testCountryTimezonesContainsKnownIdentifier(): void
    {
        /** @Given a Country created from Alpha-2 code JP (Japan) */
        $country = Country::from(alphaCode: Alpha2Code::JAPAN);

        /** @Then it should contain Asia/Tokyo */
        self::assertTrue($country->timezones->contains(iana: 'Asia/Tokyo'));

        /** @And it should not contain a timezone from another country */
        self::assertFalse($country->timezones->contains(iana: 'America/New_York'));
    }

    public function testCountryTimezonesFindByIdentifierReturnsTimezone(): void
    {
        /** @Given a Country created from Alpha-2 code US */
        $country = Country::from(alphaCode: Alpha2Code::UNITED_STATES_OF_AMERICA);

        /** @When searching for a known timezone identifier */
        $timezone = $country->timezones->findByIdentifier(iana: 'America/New_York');

        /** @Then a Timezone object should be returned */
        self::assertInstanceOf(Timezone::class, $timezone);

        /** @And its value should match the searched identifier */
        self::assertSame('America/New_York', $timezone->value);
    }

    public function testCountryTimezonesFindByIdentifierReturnsNullWhenNotFound(): void
    {
        /** @Given a Country created from Alpha-2 code DE (Germany) */
        $country = Country::from(alphaCode: Alpha2Code::GERMANY);

        /** @When searching for a timezone that does not belong to Germany */
        $timezone = $country->timezones->findByIdentifier(iana: 'Asia/Tokyo');

        /** @Then null should be returned */
        self::assertNull($timezone);
    }

    public function testCountryTimezonesCountMatchesAllSize(): void
    {
        /** @Given a Country with multiple timezones */
        $country = Country::from(alphaCode: Alpha2Code::RUSSIA);

        /** @Then count() should match the number of items in all() */
        self::assertCount($country->timezones->count(), $country->timezones->all());
    }

    public function testCountryTimezonesIsCountable(): void
    {
        /** @Given a Country with timezones */
        $country = Country::from(alphaCode: Alpha2Code::INDIA);

        /** @Then the native count() function should work on the timezones collection */
        self::assertSame($country->timezones->count(), count($country->timezones));
    }

    public function testCountryTimezonesToStringsReturnsPlainIdentifiers(): void
    {
        /** @Given a Country created from Alpha-2 code PT (Portugal) */
        $country = Country::from(alphaCode: Alpha2Code::PORTUGAL);

        /** @When converting timezones to strings */
        $strings = $country->timezones->toStrings();

        /** @Then each element should be a string matching its corresponding Timezone value */
        $all = $country->timezones->all();

        foreach ($strings as $index => $string) {
            self::assertIsString($string);
            self::assertSame($all[$index]->value, $string);
        }
    }

    public function testCountryWithSingleTimezone(): void
    {
        /** @Given a Country with exactly one timezone (e.g. Japan) */
        $country = Country::from(alphaCode: Alpha2Code::JAPAN);

        /** @Then it should have exactly one timezone */
        self::assertSame(1, $country->timezones->count());

        /** @And the default should be that single timezone */
        self::assertSame($country->timezones->all()[0]->value, $country->timezones->default()->value);
    }

    public function testCountryWithMultipleTimezonesPreservesAll(): void
    {
        /** @Given a Country known to have many timezones (e.g. United States) */
        $country = Country::from(alphaCode: Alpha2Code::UNITED_STATES_OF_AMERICA);

        /** @Then it should have more than one timezone */
        self::assertGreaterThan(1, $country->timezones->count());

        /** @And every timezone in the collection should be distinct */
        $values = $country->timezones->toStrings();
        self::assertSame($values, array_unique($values));

        /** @And each timezone should be findable by its identifier */
        foreach ($country->timezones->all() as $timezone) {
            self::assertNotNull($country->timezones->findByIdentifier(iana: $timezone->value));
        }
    }

    public function testCountryTimezonesCreatedFromSameCodeAreConsistent(): void
    {
        /** @Given two Country instances created from the same alpha code */
        $first = Country::from(alphaCode: Alpha2Code::BRAZIL);
        $second = Country::from(alphaCode: Alpha2Code::BRAZIL);

        /** @Then their timezone collections should produce the same string lists */
        self::assertSame($first->timezones->toStrings(), $second->timezones->toStrings());

        /** @And their counts should match */
        self::assertSame($first->timezones->count(), $second->timezones->count());
    }

    public function testCountryWhenInvalidTimezoneIdentifier(): void
    {
        /** @Given a non-empty string that is not a valid IANA timezone */
        $invalidIdentifier = 'Invalid/Timezone';

        /** @Then an InvalidTimezone exception should be thrown */
        $this->expectException(InvalidTimezone::class);
        $this->expectExceptionMessage(sprintf('Timezone <%s> is invalid.', $invalidIdentifier));

        /** @When trying to create a Timezone from the invalid identifier */
        Timezone::from(identifier: $invalidIdentifier);
    }

    public function testCountryWhenEmptyTimezoneIdentifier(): void
    {
        /** @Given an empty string as timezone identifier */
        /** @Then an InvalidTimezone exception should be thrown */
        $this->expectException(InvalidTimezone::class);
        $this->expectExceptionMessage('Timezone <> is invalid.');

        /** @When trying to create a Timezone from an empty string */
        Timezone::from(identifier: '');
    }

    #[DataProvider('invalidAlphaCodeStringsDataProvider')]
    public function testCountryWhenInvalidAlphaCode(string $alphaCode): void
    {
        /** @Given an invalid alpha code string */
        /** @Then an InvalidAlphaCode exception should be thrown */
        $this->expectException(InvalidAlphaCode::class);
        $this->expectExceptionMessage(sprintf('Alpha code <%s> is invalid.', $alphaCode));

        /** @When trying to create a Country from the invalid code */
        Country::fromString(alphaCode: $alphaCode);
    }

    public function testCountryWhenInvalidAlphaCodeImplementation(): void
    {
        /** @Given an AlphaCode implementation that is neither Alpha2Code nor Alpha3Code */
        $alphaCode = AlphaCodeXpto::SWITZERLAND;

        /** @Then an InvalidAlphaCodeImplementation exception should be thrown */
        $this->expectException(InvalidAlphaCodeImplementation::class);
        $this->expectExceptionMessage(
            sprintf('The alpha code implementation <%s> is invalid.', AlphaCodeXpto::class)
        );

        /** @When trying to create a Country from the unsupported implementation */
        Country::from(alphaCode: $alphaCode);
    }

    public static function alphaCodeObjectsDataProvider(): array
    {
        return [
            'Alpha2 with custom name'   => [
                'alphaCode'      => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'name'           => 'United States',
                'expectedName'   => 'United States',
                'expectedAlpha2' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expectedAlpha3' => Alpha3Code::UNITED_STATES_OF_AMERICA,
            ],
            'Alpha3 with custom name'   => [
                'alphaCode'      => Alpha3Code::UNITED_STATES_OF_AMERICA,
                'name'           => 'United States',
                'expectedName'   => 'United States',
                'expectedAlpha2' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expectedAlpha3' => Alpha3Code::UNITED_STATES_OF_AMERICA,
            ],
            'Alpha2 with null name'     => [
                'alphaCode'      => Alpha2Code::BRAZIL,
                'name'           => null,
                'expectedName'   => 'Brazil',
                'expectedAlpha2' => Alpha2Code::BRAZIL,
                'expectedAlpha3' => Alpha3Code::BRAZIL,
            ],
            'Alpha3 with null name'     => [
                'alphaCode'      => Alpha3Code::BRAZIL,
                'name'           => null,
                'expectedName'   => 'Brazil',
                'expectedAlpha2' => Alpha2Code::BRAZIL,
                'expectedAlpha3' => Alpha3Code::BRAZIL,
            ],
            'Alpha2 GB with full name'  => [
                'alphaCode'      => Alpha2Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'name'           => 'United Kingdom of Great Britain and Northern Ireland',
                'expectedName'   => 'United Kingdom of Great Britain and Northern Ireland',
                'expectedAlpha2' => Alpha2Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'expectedAlpha3' => Alpha3Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
            ],
            'Alpha3 GBR with null name' => [
                'alphaCode'      => Alpha3Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'name'           => null,
                'expectedName'   => 'United Kingdom of Great Britain and Northern Ireland',
                'expectedAlpha2' => Alpha2Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'expectedAlpha3' => Alpha3Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
            ],
        ];
    }

    public static function alphaCodeStringsDataProvider(): array
    {
        return [
            'Alpha2 string US'               => [
                'alphaCode'      => 'US',
                'name'           => 'United States',
                'expectedName'   => 'United States',
                'expectedAlpha2' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expectedAlpha3' => Alpha3Code::UNITED_STATES_OF_AMERICA,
            ],
            'Alpha3 string USA'              => [
                'alphaCode'      => 'USA',
                'name'           => 'United States',
                'expectedName'   => 'United States',
                'expectedAlpha2' => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expectedAlpha3' => Alpha3Code::UNITED_STATES_OF_AMERICA,
            ],
            'Alpha2 string with null name'   => [
                'alphaCode'      => 'BR',
                'name'           => null,
                'expectedName'   => 'Brazil',
                'expectedAlpha2' => Alpha2Code::BRAZIL,
                'expectedAlpha3' => Alpha3Code::BRAZIL,
            ],
            'Alpha3 string with null name'   => [
                'alphaCode'      => 'BRA',
                'name'           => null,
                'expectedName'   => 'Brazil',
                'expectedAlpha2' => Alpha2Code::BRAZIL,
                'expectedAlpha3' => Alpha3Code::BRAZIL,
            ],
            'Alpha2 string with custom name' => [
                'alphaCode'      => 'BR',
                'name'           => 'Brasil',
                'expectedName'   => 'Brasil',
                'expectedAlpha2' => Alpha2Code::BRAZIL,
                'expectedAlpha3' => Alpha3Code::BRAZIL,
            ],
            'Alpha3 string with custom name' => [
                'alphaCode'      => 'BRA',
                'name'           => 'Brasil',
                'expectedName'   => 'Brasil',
                'expectedAlpha2' => Alpha2Code::BRAZIL,
                'expectedAlpha3' => Alpha3Code::BRAZIL,
            ],
        ];
    }

    public static function invalidAlphaCodeStringsDataProvider(): array
    {
        return [
            'Single character' => ['alphaCode' => 'X'],
            'Two characters'   => ['alphaCode' => 'XY'],
            'Three characters' => ['alphaCode' => 'XYZ'],
            'Four characters'  => ['alphaCode' => 'XYZ1'],
        ];
    }
}
