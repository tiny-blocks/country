<?php

declare(strict_types=1);

namespace Test\TinyBlocks\Country\Unit;

use PHPUnit\Framework\TestCase;
use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Country;
use TinyBlocks\Country\Exceptions\InvalidSubdivisionCode;
use TinyBlocks\Country\Subdivision;
use TinyBlocks\Country\SubdivisionCategory;

final class SubdivisionTest extends TestCase
{
    public function testCodeReturnsIsoCode(): void
    {
        /** @Given a subdivision for São Paulo */
        $subdivision = Subdivision::fromString(code: 'BR-SP');

        /** @When reading its code */
        $code = $subdivision->code();

        /** @Then it is the ISO 3166-2 code */
        self::assertSame('BR-SP', $code);
    }

    public function testResolvesAndorraParish(): void
    {
        /** @Given the code of an Andorran parish */
        $code = 'AD-02';

        /** @When creating the subdivision from it */
        $subdivision = Subdivision::fromString(code: $code);

        /** @Then it exposes the parish name */
        self::assertSame('Canillo', $subdivision->name());

        /** @And it exposes the parish category */
        self::assertSame(SubdivisionCategory::PARISH, $subdivision->category());
    }

    public function testNameReturnsOfficialName(): void
    {
        /** @Given a subdivision for São Paulo */
        $subdivision = Subdivision::fromString(code: 'BR-SP');

        /** @When reading its name */
        $name = $subdivision->name();

        /** @Then it is the ISO 3166-2 name */
        self::assertSame('São Paulo', $name);
    }

    public function testCategoryReturnsTheCategory(): void
    {
        /** @Given a subdivision for São Paulo */
        $subdivision = Subdivision::fromString(code: 'BR-SP');

        /** @When reading its category */
        $category = $subdivision->category();

        /** @Then it is the state category */
        self::assertSame(SubdivisionCategory::STATE, $category);
    }

    public function testCountryIsDerivedFromCodePrefix(): void
    {
        /** @Given a subdivision for São Paulo */
        $subdivision = Subdivision::fromString(code: 'BR-SP');

        /** @When reading its country */
        $country = $subdivision->country();

        /** @Then it is the country named by the code prefix */
        self::assertSame(Alpha2Code::BRAZIL, $country->alpha2());
    }

    public function testCountryCodeIsDerivedFromCodePrefix(): void
    {
        /** @Given a subdivision for São Paulo */
        $subdivision = Subdivision::fromString(code: 'BR-SP');

        /** @When reading its country code */
        $countryCode = $subdivision->countryCode();

        /** @Then it is the Alpha-2 code named by the code prefix */
        self::assertSame(Alpha2Code::BRAZIL, $countryCode);
    }

    public function testTryFromStringWhenUnknownThenReturnsNull(): void
    {
        /** @Given an unknown subdivision code */
        $code = 'BR-XX';

        /** @When trying to create a subdivision from it */
        $subdivision = Subdivision::tryFromString(code: $code);

        /** @Then null is returned */
        self::assertNull($subdivision);
    }

    public function testBelongsToWhenOwningCountryThenReturnsTrue(): void
    {
        /** @Given a subdivision for São Paulo */
        $subdivision = Subdivision::fromString(code: 'BR-SP');

        /** @When testing ownership against its owning country */
        $belongsToBrazil = $subdivision->belongsTo(country: Country::fromString(code: 'BR'));

        /** @Then it belongs to that country */
        self::assertTrue($belongsToBrazil);
    }

    public function testFromStringWhenKnownThenCreatesSubdivision(): void
    {
        /** @Given a known ISO 3166-2 code */
        $code = 'BR-SP';

        /** @When creating a subdivision from it */
        $subdivision = Subdivision::fromString(code: $code);

        /** @Then the subdivision exposes the code */
        self::assertSame('BR-SP', $subdivision->code());
    }

    public function testEqualsWhenSameCodeThenSubdivisionsAreEqual(): void
    {
        /** @Given a subdivision for São Paulo */
        $subdivision = Subdivision::fromString(code: 'BR-SP');

        /** @And another subdivision for the same code */
        $other = Subdivision::fromString(code: 'BR-SP');

        /** @When comparing them */
        $areEqual = $subdivision->equals(other: $other);

        /** @Then they are equal */
        self::assertTrue($areEqual);
    }

    public function testTryFromStringWhenKnownThenReturnsSubdivision(): void
    {
        /** @Given a known ISO 3166-2 code */
        $code = 'BR-SP';

        /** @When trying to create a subdivision from it */
        $subdivision = Subdivision::tryFromString(code: $code);

        /** @Then a subdivision is returned */
        self::assertInstanceOf(Subdivision::class, $subdivision);
    }

    public function testBelongsToWhenDifferentCountryThenReturnsFalse(): void
    {
        /** @Given a subdivision for São Paulo */
        $subdivision = Subdivision::fromString(code: 'BR-SP');

        /** @When testing ownership against a different country */
        $belongsToUnitedStates = $subdivision->belongsTo(country: Country::fromString(code: 'US'));

        /** @Then it does not belong to that country */
        self::assertFalse($belongsToUnitedStates);
    }

    public function testEqualsWhenDifferentCodeThenSubdivisionsDiffer(): void
    {
        /** @Given a subdivision for São Paulo */
        $subdivision = Subdivision::fromString(code: 'BR-SP');

        /** @And another subdivision for a different code */
        $other = Subdivision::fromString(code: 'BR-RJ');

        /** @When comparing them */
        $areEqual = $subdivision->equals(other: $other);

        /** @Then they are not equal */
        self::assertFalse($areEqual);
    }

    public function testFromStringWhenUnknownThenThrowsInvalidSubdivisionCode(): void
    {
        /** @Given an unknown subdivision code */
        $code = 'ZZ-99';

        /** @And a template describing the expected error message */
        $template = 'Subdivision code <%s> is invalid.';

        /** @Then an InvalidSubdivisionCode exception is thrown */
        $this->expectException(InvalidSubdivisionCode::class);

        /** @And the message identifies the invalid input */
        $this->expectExceptionMessage(sprintf($template, $code));

        /** @When creating a subdivision from the invalid code */
        Subdivision::fromString(code: $code);
    }

    public function testTryFromStringWhenCountryHasNoSubdivisionsThenReturnsNull(): void
    {
        /** @Given a code whose country has no subdivisions */
        $code = 'AQ-01';

        /** @When trying to create a subdivision from it */
        $subdivision = Subdivision::tryFromString(code: $code);

        /** @Then null is returned */
        self::assertNull($subdivision);
    }
}
