<?php

declare(strict_types=1);

namespace Test\TinyBlocks\Country\Unit;

use PHPUnit\Framework\TestCase;
use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\CountryTimezones;
use TinyBlocks\Time\Timezone;

final class CountryTimezonesTest extends TestCase
{
    public function testCountMatchesNumberOfEntries(): void
    {
        /** @Given the timezones for a country */
        $timezones = CountryTimezones::fromAlpha2(alpha2: Alpha2Code::RUSSIA);

        /** @When counting them */
        $count = $timezones->count();

        /** @Then it equals the number of entries returned by all */
        self::assertCount($count, $timezones->all());
    }

    public function testIsCountableThroughNativeCount(): void
    {
        /** @Given the timezones for a country */
        $timezones = CountryTimezones::fromAlpha2(alpha2: Alpha2Code::INDIA);

        /** @When applying the native count function */
        $nativeCount = count($timezones);

        /** @Then it equals the count method */
        self::assertSame($timezones->count(), $nativeCount);
    }

    public function testAllEntriesAreTimezoneInstances(): void
    {
        /** @Given the timezones for a country */
        $timezones = CountryTimezones::fromAlpha2(alpha2: Alpha2Code::BRAZIL);

        /** @When iterating the entries */
        $entries = $timezones->all();

        /** @Then every entry is a Timezone instance */
        foreach ($entries as $entry) {
            self::assertInstanceOf(Timezone::class, $entry);
        }
    }

    public function testDefaultFallsBackToUtcWhenEmpty(): void
    {
        /** @Given the timezones for a country with no IANA entries */
        $timezones = CountryTimezones::fromAlpha2(alpha2: Alpha2Code::BOUVET_ISLAND);

        /** @When reading the default */
        $default = $timezones->default();

        /** @Then it falls back to UTC */
        self::assertSame('UTC', $default->value);
    }

    public function testDefaultReturnsTheFirstTimezone(): void
    {
        /** @Given the timezones for a country */
        $timezones = CountryTimezones::fromAlpha2(alpha2: Alpha2Code::BRAZIL);

        /** @When reading the default */
        $default = $timezones->default();

        /** @Then it is the first entry */
        self::assertSame($timezones->all()[0]->value, $default->value);
    }

    public function testToStringsReturnsPlainIdentifiers(): void
    {
        /** @Given the timezones for Portugal */
        $timezones = CountryTimezones::fromAlpha2(alpha2: Alpha2Code::PORTUGAL);

        /** @When converting them to strings */
        $strings = $timezones->toStrings();

        /** @Then each entry equals the value of its Timezone */
        foreach ($strings as $index => $string) {
            self::assertSame($timezones->all()[$index]->value, $string);
        }
    }

    public function testCountIsOneForSingleTimezoneCountry(): void
    {
        /** @Given the timezones for a single-timezone country */
        $timezones = CountryTimezones::fromAlpha2(alpha2: Alpha2Code::JAPAN);

        /** @When counting them */
        $count = $timezones->count();

        /** @Then there is exactly one */
        self::assertSame(1, $count);
    }

    public function testContainsWhenIdentifierBelongsThenTrue(): void
    {
        /** @Given the timezones for Japan */
        $timezones = CountryTimezones::fromAlpha2(alpha2: Alpha2Code::JAPAN);

        /** @When checking a known Japanese identifier */
        $contains = $timezones->contains(iana: 'Asia/Tokyo');

        /** @Then it is recognized */
        self::assertTrue($contains);
    }

    public function testContainsWhenIdentifierForeignThenFalse(): void
    {
        /** @Given the timezones for Japan */
        $timezones = CountryTimezones::fromAlpha2(alpha2: Alpha2Code::JAPAN);

        /** @When checking an identifier from another country */
        $contains = $timezones->contains(iana: 'America/New_York');

        /** @Then it is rejected */
        self::assertFalse($contains);
    }

    public function testFindByIdentifierOrUtcFallsBackWhenMissing(): void
    {
        /** @Given the timezones for Japan */
        $timezones = CountryTimezones::fromAlpha2(alpha2: Alpha2Code::JAPAN);

        /** @When searching for an identifier from another country */
        $timezone = $timezones->findByIdentifierOrUtc(iana: 'America/New_York');

        /** @Then it falls back to UTC */
        self::assertSame('UTC', $timezone->value);
    }

    public function testFindByIdentifierOrUtcReturnsMatchWhenFound(): void
    {
        /** @Given the timezones for the United States */
        $timezones = CountryTimezones::fromAlpha2(alpha2: Alpha2Code::UNITED_STATES_OF_AMERICA);

        /** @When searching for a known identifier */
        $timezone = $timezones->findByIdentifierOrUtc(iana: 'America/New_York');

        /** @Then the matching Timezone is returned */
        self::assertSame('America/New_York', $timezone->value);
    }
}
