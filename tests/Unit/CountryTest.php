<?php

declare(strict_types=1);

namespace Test\TinyBlocks\Country\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\TinyBlocks\Country\Models\CountryCodeXpto;
use Test\TinyBlocks\Country\Models\CountryWithCallingCode;
use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Alpha3Code;
use TinyBlocks\Country\Country;
use TinyBlocks\Country\CountryCode;
use TinyBlocks\Country\Exceptions\InvalidCountryCode;
use TinyBlocks\Country\Exceptions\InvalidCountryCodeImplementation;
use TinyBlocks\Country\NumericCode;
use TinyBlocks\Time\Timezone;

final class CountryTest extends TestCase
{
    #[DataProvider('countryCodeObjectsDataProvider')]
    public function testCountryFromCountryCodeObject(
        CountryCode $code,
        ?string $name,
        string $expectedName,
        Alpha2Code $expectedAlpha2,
        Alpha3Code $expectedAlpha3,
        NumericCode $expectedNumeric
    ): void {
        /** @Given a valid CountryCode object and an optional custom name */

        /** @When creating a Country from the alpha code */
        $country = Country::from(code: $code, name: $name);

        /** @Then the resulting Country exposes the expected name */
        self::assertSame($expectedName, $country->name);

        /** @And it exposes the expected Alpha-2 code */
        self::assertSame($expectedAlpha2, $country->alpha2);

        /** @And it exposes the expected Alpha-3 code */
        self::assertSame($expectedAlpha3, $country->alpha3);

        /** @And it exposes the expected numeric code */
        self::assertSame($expectedNumeric, $country->numeric);
    }

    #[DataProvider('countryCodeStringsDataProvider')]
    public function testCountryFromCountryCodeString(
        string $code,
        ?string $name,
        string $expectedName,
        Alpha2Code $expectedAlpha2,
        Alpha3Code $expectedAlpha3,
        NumericCode $expectedNumeric
    ): void {
        /** @Given a valid country code as a string and an optional custom name */

        /** @When creating a Country from the string */
        $country = Country::fromString(code: $code, name: $name);

        /** @Then the resulting Country exposes the expected name */
        self::assertSame($expectedName, $country->name);

        /** @And it exposes the expected Alpha-2 code */
        self::assertSame($expectedAlpha2, $country->alpha2);

        /** @And it exposes the expected Alpha-3 code */
        self::assertSame($expectedAlpha3, $country->alpha3);

        /** @And it exposes the expected numeric code */
        self::assertSame($expectedNumeric, $country->numeric);
    }

    public function testCountryNameIsDerivedFromAlphaCodeWhenNotProvided(): void
    {
        /** @Given an Alpha-2 code without a custom name */
        $code = Alpha2Code::UNITED_STATES_OF_AMERICA;

        /** @When creating a Country without specifying a name */
        $country = Country::from(code: $code);

        /** @Then the name is derived from the enum case in human-readable form */
        self::assertSame('United States of America', $country->name);
    }

    public function testCountryNamePreservesCustomNameWhenProvided(): void
    {
        /** @Given an Alpha-3 code with a custom name */
        $code = Alpha3Code::BRAZIL;

        /** @When creating a Country with the custom name */
        $country = Country::from(code: $code, name: 'Brasil');

        /** @Then the custom name is preserved */
        self::assertSame('Brasil', $country->name);
    }

    public function testCountryNameNormalizesPrepositionsToLowercase(): void
    {
        /** @Given an Alpha-2 code whose enum name contains prepositions (of, and, the) */
        $code = Alpha2Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND;

        /** @When creating a Country without a custom name */
        $country = Country::from(code: $code);

        /** @Then prepositions are lowercased in the derived name */
        self::assertSame('United Kingdom of Great Britain and Northern Ireland', $country->name);
    }

    public function testCountryAlpha2ConvertsToAlpha3Correctly(): void
    {
        /** @Given an Alpha-2 code for Japan */
        $alpha2 = Alpha2Code::JAPAN;

        /** @When creating a Country from the Alpha-2 code */
        $country = Country::from(code: $alpha2);

        /** @Then the alpha-3 code corresponds to the same country */
        self::assertSame(Alpha3Code::JAPAN, $country->alpha3);

        /** @And the alpha-2 code is preserved */
        self::assertSame($alpha2, $country->alpha2);
    }

    public function testCountryAlpha3ConvertsToAlpha2Correctly(): void
    {
        /** @Given an Alpha-3 code for Switzerland */
        $alpha3 = Alpha3Code::SWITZERLAND;

        /** @When creating a Country from the Alpha-3 code */
        $country = Country::from(code: $alpha3);

        /** @Then the alpha-2 code corresponds to the same country */
        self::assertSame(Alpha2Code::SWITZERLAND, $country->alpha2);

        /** @And the alpha-3 code is preserved */
        self::assertSame($alpha3, $country->alpha3);
    }

    public function testCountryNumericConvertsToAlpha2AndAlpha3Correctly(): void
    {
        /** @Given a numeric code for Brazil */
        $numeric = NumericCode::BRAZIL;

        /** @When creating a Country from the numeric code */
        $country = Country::from(code: $numeric);

        /** @Then the alpha-2 code corresponds to the same country */
        self::assertSame(Alpha2Code::BRAZIL, $country->alpha2);

        /** @And the alpha-3 code corresponds to the same country */
        self::assertSame(Alpha3Code::BRAZIL, $country->alpha3);

        /** @And the numeric code is preserved */
        self::assertSame($numeric, $country->numeric);
    }

    #[DataProvider('alpha2ToStringDataProvider')]
    public function testCountryAlpha2ToStringReturnsTwoLetterValue(
        Alpha2Code $alpha2,
        string $expectedAlpha2String
    ): void {
        /** @Given a Country created from an Alpha-2 code */
        $country = Country::from(code: $alpha2);

        /** @When calling toString on the alpha-2 code */
        $alpha2String = $country->alpha2->toString();

        /** @Then it returns the two-letter code */
        self::assertSame($expectedAlpha2String, $alpha2String);
    }

    #[DataProvider('alpha3ToStringDataProvider')]
    public function testCountryAlpha3ToStringReturnsThreeLetterValue(
        Alpha2Code $alpha2,
        string $expectedAlpha3String
    ): void {
        /** @Given a Country created from an Alpha-2 code */
        $country = Country::from(code: $alpha2);

        /** @When calling toString on the alpha-3 code */
        $alpha3String = $country->alpha3->toString();

        /** @Then it returns the three-letter code */
        self::assertSame($expectedAlpha3String, $alpha3String);
    }

    #[DataProvider('numericToStringDataProvider')]
    public function testCountryNumericToStringReturnsThreeDigitValue(
        Alpha2Code $alpha2,
        string $expectedNumericString
    ): void {
        /** @Given a Country created from an Alpha-2 code */
        $country = Country::from(code: $alpha2);

        /** @When calling toString on the numeric code */
        $numericString = $country->numeric->toString();

        /** @Then it returns the three-digit code */
        self::assertSame($expectedNumericString, $numericString);
    }

    #[DataProvider('numericToIntegerDataProvider')]
    public function testCountryNumericToIntegerReturnsInteger(NumericCode $numeric, int $expected): void
    {
        /** @Given a NumericCode case */

        /** @When calling toInteger on the numeric code */
        $integer = $numeric->toInteger();

        /** @Then it returns the numeric value as an integer */
        self::assertSame($expected, $integer);
    }

    #[DataProvider('validCountryCodeStringsDataProvider')]
    public function testCountryTryFromStringReturnsCountryForValidCode(string $code, Alpha2Code $expectedAlpha2): void
    {
        /** @Given a valid country code string */

        /** @When trying to create a Country from the string */
        $country = Country::tryFromString(code: $code);

        /** @Then a Country instance is returned */
        self::assertInstanceOf(Country::class, $country);

        /** @And the alpha-2 code matches the resolved country */
        self::assertSame($expectedAlpha2, $country->alpha2);
    }

    #[DataProvider('invalidCountryCodeStringsDataProvider')]
    public function testCountryTryFromStringReturnsNullForInvalidCode(string $code): void
    {
        /** @Given an invalid country code string */

        /** @When trying to create a Country from the invalid code */
        $country = Country::tryFromString(code: $code);

        /** @Then null is returned */
        self::assertNull($country);
    }

    public function testCountryFromStringWithAlpha2Code(): void
    {
        /** @Given a valid two-letter alpha code string */
        $code = 'BR';

        /** @When creating a Country from the string */
        $country = Country::fromString(code: $code);

        /** @Then the alpha-2 code matches the resolved country */
        self::assertSame(Alpha2Code::BRAZIL, $country->alpha2);

        /** @And the alpha-3 code matches the resolved country */
        self::assertSame(Alpha3Code::BRAZIL, $country->alpha3);
    }

    public function testCountryFromStringWithAlpha3Code(): void
    {
        /** @Given a valid three-letter alpha code string */
        $code = 'USA';

        /** @When creating a Country from the string */
        $country = Country::fromString(code: $code);

        /** @Then the alpha-2 code matches the resolved country */
        self::assertSame(Alpha2Code::UNITED_STATES_OF_AMERICA, $country->alpha2);

        /** @And the alpha-3 code matches the resolved country */
        self::assertSame(Alpha3Code::UNITED_STATES_OF_AMERICA, $country->alpha3);
    }

    public function testCountryFromStringWithNumericCode(): void
    {
        /** @Given a valid three-digit numeric code string */
        $code = '076';

        /** @When creating a Country from the string */
        $country = Country::fromString(code: $code);

        /** @Then the alpha-2 code matches the resolved country */
        self::assertSame(Alpha2Code::BRAZIL, $country->alpha2);

        /** @And the alpha-3 code matches the resolved country */
        self::assertSame(Alpha3Code::BRAZIL, $country->alpha3);

        /** @And the numeric code matches the resolved country */
        self::assertSame(NumericCode::BRAZIL, $country->numeric);
    }

    public function testAlpha2AndAlpha3AndNumericRepresentSameCountries(): void
    {
        /** @Given the case names of every Alpha2Code value */
        $alpha2Names = array_map(static fn(Alpha2Code $code): string => $code->name, Alpha2Code::cases());

        /** @And the case names of every Alpha3Code value */
        $alpha3Names = array_map(static fn(Alpha3Code $code): string => $code->name, Alpha3Code::cases());

        /** @When fetching the case names of every NumericCode value */
        $numericNames = array_map(static fn(NumericCode $code): string => $code->name, NumericCode::cases());

        /** @Then all three lists are identical in content and order */
        self::assertSame($alpha2Names, $alpha3Names);
        self::assertSame($alpha2Names, $numericNames);
    }

    public function testCountryCodeConversionsArePairwiseIdentity(): void
    {
        /** @Given a lookup map for every Alpha3Code by case name */
        $alpha3ByName = array_combine(
            array_map(static fn(Alpha3Code $code): string => $code->name, Alpha3Code::cases()),
            Alpha3Code::cases()
        );

        /** @And a lookup map for every NumericCode by case name */
        $numericByName = array_combine(
            array_map(static fn(NumericCode $code): string => $code->name, NumericCode::cases()),
            NumericCode::cases()
        );

        /** @When converting each Alpha2Code to its Alpha-3 and numeric counterparts in both directions */
        foreach (Alpha2Code::cases() as $alpha2) {
            $alpha3 = $alpha3ByName[$alpha2->name];
            $numeric = $numericByName[$alpha2->name];

            /** @Then converting between any two enums yields the matching case in every direction */
            self::assertSame($alpha3, $alpha2->toAlpha3());
            self::assertSame($numeric, $alpha2->toNumeric());
            self::assertSame($alpha2, $alpha3->toAlpha2());
            self::assertSame($numeric, $alpha3->toNumeric());
            self::assertSame($alpha2, $numeric->toAlpha2());
            self::assertSame($alpha3, $numeric->toAlpha3());
        }
    }

    public function testCountryCanBeExtendedWithAdditionalProperty(): void
    {
        /** @Given an extended Country subclass that adds a calling code property */

        /** @When creating an instance through the subclass factory */
        $country = CountryWithCallingCode::withCallingCode(code: Alpha2Code::BRAZIL, callingCode: '+55');

        /** @Then the inherited Country properties are preserved */
        self::assertSame('Brazil', $country->name);
        self::assertSame(Alpha2Code::BRAZIL, $country->alpha2);
        self::assertSame(Alpha3Code::BRAZIL, $country->alpha3);
        self::assertSame(NumericCode::BRAZIL, $country->numeric);

        /** @And the additional property is exposed */
        self::assertSame('+55', $country->callingCode);
    }

    public function testCountryHasTimezones(): void
    {
        /** @Given an Alpha-2 code for a country with known timezones */
        $code = Alpha2Code::BRAZIL;

        /** @When creating a Country from the Alpha-2 code */
        $country = Country::from(code: $code);

        /** @Then the timezones collection is not empty */
        self::assertGreaterThan(0, $country->timezones->count());

        /** @And every entry in the collection is a Timezone instance */
        foreach ($country->timezones->all() as $timezone) {
            self::assertInstanceOf(Timezone::class, $timezone);
        }
    }

    public function testCountryTimezonesDefaultReturnsFirstTimezone(): void
    {
        /** @Given a Country created from Brazil's Alpha-2 code */
        $country = Country::from(code: Alpha2Code::BRAZIL);

        /** @When retrieving the default timezone */
        $default = $country->timezones->default();

        /** @Then it is the first timezone in the collection */
        self::assertSame($country->timezones->all()[0]->value, $default->value);
    }

    public function testCountryTimezonesDefaultFallsBackToUtc(): void
    {
        /** @Given a Country whose Alpha-2 code yields no IANA timezones */
        $country = Country::from(code: Alpha2Code::BOUVET_ISLAND);

        /** @When retrieving the default timezone */
        $default = $country->timezones->default();

        /** @Then it falls back to UTC */
        self::assertSame('UTC', $default->value);
    }

    public function testCountryTimezonesContainsKnownIdentifier(): void
    {
        /** @Given a Country created from Japan's Alpha-2 code */
        $country = Country::from(code: Alpha2Code::JAPAN);

        /** @When checking whether the collection contains a known Japanese identifier */
        $contains = $country->timezones->contains(iana: 'Asia/Tokyo');

        /** @Then the identifier is recognized */
        self::assertTrue($contains);
    }

    public function testCountryTimezonesDoesNotContainUnrelatedIdentifier(): void
    {
        /** @Given a Country created from Japan's Alpha-2 code */
        $country = Country::from(code: Alpha2Code::JAPAN);

        /** @When checking whether the collection contains an identifier from another country */
        $contains = $country->timezones->contains(iana: 'America/New_York');

        /** @Then the identifier is rejected */
        self::assertFalse($contains);
    }

    public function testCountryTimezonesFindByIdentifierOrUtcReturnsTimezone(): void
    {
        /** @Given a Country created from the United States Alpha-2 code */
        $country = Country::from(code: Alpha2Code::UNITED_STATES_OF_AMERICA);

        /** @When searching for a known timezone identifier */
        $timezone = $country->timezones->findByIdentifierOrUtc(iana: 'America/New_York');

        /** @Then the returned Timezone value matches the searched identifier */
        self::assertSame('America/New_York', $timezone->value);
    }

    public function testCountryTimezonesCountMatchesAllSize(): void
    {
        /** @Given a Country with multiple timezones */
        $country = Country::from(code: Alpha2Code::RUSSIA);

        /** @When invoking count() on the timezones collection */
        $count = $country->timezones->count();

        /** @Then it equals the number of entries returned by all() */
        self::assertCount($count, $country->timezones->all());
    }

    public function testCountryTimezonesIsCountable(): void
    {
        /** @Given a Country with timezones */
        $country = Country::from(code: Alpha2Code::INDIA);

        /** @When invoking the native count() function on the timezones */
        $nativeCount = count($country->timezones);

        /** @Then it returns the same value as the count() method */
        self::assertSame($country->timezones->count(), $nativeCount);
    }

    public function testCountryTimezonesToStringsReturnsPlainIdentifiers(): void
    {
        /** @Given a Country created from Portugal's Alpha-2 code */
        $country = Country::from(code: Alpha2Code::PORTUGAL);

        /** @And the list of Timezone objects from the same country */
        $all = $country->timezones->all();

        /** @When converting the timezones to strings */
        $strings = $country->timezones->toStrings();

        /** @Then every entry equals the value of its corresponding Timezone */
        foreach ($strings as $index => $string) {
            self::assertSame($all[$index]->value, $string);
        }
    }

    public function testCountryWithSingleTimezone(): void
    {
        /** @Given an Alpha-2 code for a country with exactly one timezone */
        $code = Alpha2Code::JAPAN;

        /** @When creating a Country from the Alpha-2 code */
        $country = Country::from(code: $code);

        /** @Then the timezones count is one */
        self::assertSame(1, $country->timezones->count());

        /** @And the default equals the single timezone */
        self::assertSame($country->timezones->all()[0]->value, $country->timezones->default()->value);
    }

    public function testCountryWithMultipleTimezonesPreservesAll(): void
    {
        /** @Given a Country known to have many timezones */
        $country = Country::from(code: Alpha2Code::UNITED_STATES_OF_AMERICA);

        /** @When retrieving the timezone string values */
        $values = $country->timezones->toStrings();

        /** @Then the country has more than one timezone */
        self::assertGreaterThan(1, $country->timezones->count());

        /** @And every timezone in the collection is distinct */
        self::assertSame($values, array_unique($values));

        /** @And every timezone is findable by its identifier */
        foreach ($country->timezones->all() as $timezone) {
            $resolved = $country->timezones->findByIdentifierOrUtc(iana: $timezone->value);
            self::assertSame($timezone->value, $resolved->value);
        }
    }

    public function testCountryTimezonesCreatedFromSameCodeAreConsistent(): void
    {
        /** @Given an Alpha-2 code for Brazil */
        $code = Alpha2Code::BRAZIL;

        /** @When invoked twice with the same code */
        $first = Country::from(code: $code);
        $second = Country::from(code: $code);

        /** @Then both Country instances expose the same timezone strings */
        self::assertSame($first->timezones->toStrings(), $second->timezones->toStrings());

        /** @And both Country instances report the same timezone count */
        self::assertSame($first->timezones->count(), $second->timezones->count());
    }

    #[DataProvider('invalidCountryCodeStringsDataProvider')]
    public function testCountryWhenInvalidCountryCode(string $code): void
    {
        /** @Given an invalid country code string */

        /** @And a template describing the expected error message */
        $template = 'Country code <%s> is invalid.';

        /** @Then an InvalidCountryCode exception is thrown */
        $this->expectException(InvalidCountryCode::class);

        /** @And the message identifies the invalid input */
        $this->expectExceptionMessage(sprintf($template, $code));

        /** @When trying to create a Country from the invalid code */
        Country::fromString(code: $code);
    }

    public function testCountryWhenInvalidCountryCodeImplementation(): void
    {
        /** @Given a CountryCode implementation that is neither Alpha2Code, Alpha3Code, nor NumericCode */
        $code = CountryCodeXpto::SWITZERLAND;

        /** @And a template describing the expected error message */
        $template = 'The country code implementation <%s> is invalid.';

        /** @Then an InvalidCountryCodeImplementation exception is thrown */
        $this->expectException(InvalidCountryCodeImplementation::class);

        /** @And the message identifies the unsupported class */
        $this->expectExceptionMessage(sprintf($template, CountryCodeXpto::class));

        /** @When trying to create a Country from the unsupported implementation */
        Country::from(code: $code);
    }

    public static function countryCodeObjectsDataProvider(): array
    {
        return [
            'Alpha2 with null name'       => [
                'code'            => Alpha2Code::BRAZIL,
                'name'            => null,
                'expectedName'    => 'Brazil',
                'expectedAlpha2'  => Alpha2Code::BRAZIL,
                'expectedAlpha3'  => Alpha3Code::BRAZIL,
                'expectedNumeric' => NumericCode::BRAZIL
            ],
            'Alpha3 with null name'       => [
                'code'            => Alpha3Code::BRAZIL,
                'name'            => null,
                'expectedName'    => 'Brazil',
                'expectedAlpha2'  => Alpha2Code::BRAZIL,
                'expectedAlpha3'  => Alpha3Code::BRAZIL,
                'expectedNumeric' => NumericCode::BRAZIL
            ],
            'Alpha2 with custom name'     => [
                'code'            => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'name'            => 'United States',
                'expectedName'    => 'United States',
                'expectedAlpha2'  => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expectedAlpha3'  => Alpha3Code::UNITED_STATES_OF_AMERICA,
                'expectedNumeric' => NumericCode::UNITED_STATES_OF_AMERICA
            ],
            'Alpha3 with custom name'     => [
                'code'            => Alpha3Code::UNITED_STATES_OF_AMERICA,
                'name'            => 'United States',
                'expectedName'    => 'United States',
                'expectedAlpha2'  => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expectedAlpha3'  => Alpha3Code::UNITED_STATES_OF_AMERICA,
                'expectedNumeric' => NumericCode::UNITED_STATES_OF_AMERICA
            ],
            'Alpha2 GB with full name'    => [
                'code'            => Alpha2Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'name'            => 'United Kingdom of Great Britain and Northern Ireland',
                'expectedName'    => 'United Kingdom of Great Britain and Northern Ireland',
                'expectedAlpha2'  => Alpha2Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'expectedAlpha3'  => Alpha3Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'expectedNumeric' => NumericCode::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND
            ],
            'Alpha3 GBR with null name'   => [
                'code'            => Alpha3Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'name'            => null,
                'expectedName'    => 'United Kingdom of Great Britain and Northern Ireland',
                'expectedAlpha2'  => Alpha2Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'expectedAlpha3'  => Alpha3Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'expectedNumeric' => NumericCode::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND
            ],
            'Numeric BR with null name'   => [
                'code'            => NumericCode::BRAZIL,
                'name'            => null,
                'expectedName'    => 'Brazil',
                'expectedAlpha2'  => Alpha2Code::BRAZIL,
                'expectedAlpha3'  => Alpha3Code::BRAZIL,
                'expectedNumeric' => NumericCode::BRAZIL
            ],
            'Numeric US with custom name' => [
                'code'            => NumericCode::UNITED_STATES_OF_AMERICA,
                'name'            => 'United States',
                'expectedName'    => 'United States',
                'expectedAlpha2'  => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expectedAlpha3'  => Alpha3Code::UNITED_STATES_OF_AMERICA,
                'expectedNumeric' => NumericCode::UNITED_STATES_OF_AMERICA
            ],
            'Numeric GB with full name'   => [
                'code'            => NumericCode::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'name'            => null,
                'expectedName'    => 'United Kingdom of Great Britain and Northern Ireland',
                'expectedAlpha2'  => Alpha2Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'expectedAlpha3'  => Alpha3Code::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND,
                'expectedNumeric' => NumericCode::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND
            ]
        ];
    }

    public static function countryCodeStringsDataProvider(): array
    {
        return [
            'Alpha2 string US'                    => [
                'code'            => 'US',
                'name'            => 'United States',
                'expectedName'    => 'United States',
                'expectedAlpha2'  => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expectedAlpha3'  => Alpha3Code::UNITED_STATES_OF_AMERICA,
                'expectedNumeric' => NumericCode::UNITED_STATES_OF_AMERICA
            ],
            'Alpha3 string USA'                   => [
                'code'            => 'USA',
                'name'            => 'United States',
                'expectedName'    => 'United States',
                'expectedAlpha2'  => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expectedAlpha3'  => Alpha3Code::UNITED_STATES_OF_AMERICA,
                'expectedNumeric' => NumericCode::UNITED_STATES_OF_AMERICA
            ],
            'Alpha2 string with null name'        => [
                'code'            => 'BR',
                'name'            => null,
                'expectedName'    => 'Brazil',
                'expectedAlpha2'  => Alpha2Code::BRAZIL,
                'expectedAlpha3'  => Alpha3Code::BRAZIL,
                'expectedNumeric' => NumericCode::BRAZIL
            ],
            'Alpha3 string with null name'        => [
                'code'            => 'BRA',
                'name'            => null,
                'expectedName'    => 'Brazil',
                'expectedAlpha2'  => Alpha2Code::BRAZIL,
                'expectedAlpha3'  => Alpha3Code::BRAZIL,
                'expectedNumeric' => NumericCode::BRAZIL
            ],
            'Alpha2 string with custom name'      => [
                'code'            => 'BR',
                'name'            => 'Brasil',
                'expectedName'    => 'Brasil',
                'expectedAlpha2'  => Alpha2Code::BRAZIL,
                'expectedAlpha3'  => Alpha3Code::BRAZIL,
                'expectedNumeric' => NumericCode::BRAZIL
            ],
            'Alpha3 string with custom name'      => [
                'code'            => 'BRA',
                'name'            => 'Brasil',
                'expectedName'    => 'Brasil',
                'expectedAlpha2'  => Alpha2Code::BRAZIL,
                'expectedAlpha3'  => Alpha3Code::BRAZIL,
                'expectedNumeric' => NumericCode::BRAZIL
            ],
            'Numeric string 076 with null name'   => [
                'code'            => '076',
                'name'            => null,
                'expectedName'    => 'Brazil',
                'expectedAlpha2'  => Alpha2Code::BRAZIL,
                'expectedAlpha3'  => Alpha3Code::BRAZIL,
                'expectedNumeric' => NumericCode::BRAZIL
            ],
            'Numeric string 840 with custom name' => [
                'code'            => '840',
                'name'            => 'United States',
                'expectedName'    => 'United States',
                'expectedAlpha2'  => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expectedAlpha3'  => Alpha3Code::UNITED_STATES_OF_AMERICA,
                'expectedNumeric' => NumericCode::UNITED_STATES_OF_AMERICA
            ]
        ];
    }

    public static function alpha2ToStringDataProvider(): array
    {
        return [
            'Japan'         => [
                'alpha2'               => Alpha2Code::JAPAN,
                'expectedAlpha2String' => 'JP'
            ],
            'Brazil'        => [
                'alpha2'               => Alpha2Code::BRAZIL,
                'expectedAlpha2String' => 'BR'
            ],
            'Germany'       => [
                'alpha2'               => Alpha2Code::GERMANY,
                'expectedAlpha2String' => 'DE'
            ],
            'Switzerland'   => [
                'alpha2'               => Alpha2Code::SWITZERLAND,
                'expectedAlpha2String' => 'CH'
            ],
            'United States' => [
                'alpha2'               => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expectedAlpha2String' => 'US'
            ]
        ];
    }

    public static function alpha3ToStringDataProvider(): array
    {
        return [
            'Japan'         => [
                'alpha2'               => Alpha2Code::JAPAN,
                'expectedAlpha3String' => 'JPN'
            ],
            'Brazil'        => [
                'alpha2'               => Alpha2Code::BRAZIL,
                'expectedAlpha3String' => 'BRA'
            ],
            'Germany'       => [
                'alpha2'               => Alpha2Code::GERMANY,
                'expectedAlpha3String' => 'DEU'
            ],
            'Switzerland'   => [
                'alpha2'               => Alpha2Code::SWITZERLAND,
                'expectedAlpha3String' => 'CHE'
            ],
            'United States' => [
                'alpha2'               => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expectedAlpha3String' => 'USA'
            ]
        ];
    }

    public static function numericToStringDataProvider(): array
    {
        return [
            'Japan'         => [
                'alpha2'                => Alpha2Code::JAPAN,
                'expectedNumericString' => '392'
            ],
            'Brazil'        => [
                'alpha2'                => Alpha2Code::BRAZIL,
                'expectedNumericString' => '076'
            ],
            'Germany'       => [
                'alpha2'                => Alpha2Code::GERMANY,
                'expectedNumericString' => '276'
            ],
            'Switzerland'   => [
                'alpha2'                => Alpha2Code::SWITZERLAND,
                'expectedNumericString' => '756'
            ],
            'United States' => [
                'alpha2'                => Alpha2Code::UNITED_STATES_OF_AMERICA,
                'expectedNumericString' => '840'
            ]
        ];
    }

    public static function numericToIntegerDataProvider(): array
    {
        return [
            'Brazil (leading zero discarded)' => ['numeric' => NumericCode::BRAZIL, 'expected' => 76],
            'United States of America'        => ['numeric' => NumericCode::UNITED_STATES_OF_AMERICA, 'expected' => 840]
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

    public static function validCountryCodeStringsDataProvider(): array
    {
        return [
            'Alpha-2 BR'  => ['code' => 'BR', 'expectedAlpha2' => Alpha2Code::BRAZIL],
            'Alpha-2 US'  => ['code' => 'US', 'expectedAlpha2' => Alpha2Code::UNITED_STATES_OF_AMERICA],
            'Alpha-3 BRA' => ['code' => 'BRA', 'expectedAlpha2' => Alpha2Code::BRAZIL],
            'Alpha-3 USA' => ['code' => 'USA', 'expectedAlpha2' => Alpha2Code::UNITED_STATES_OF_AMERICA],
            'Numeric 076' => ['code' => '076', 'expectedAlpha2' => Alpha2Code::BRAZIL],
            'Numeric 840' => ['code' => '840', 'expectedAlpha2' => Alpha2Code::UNITED_STATES_OF_AMERICA]
        ];
    }
}
