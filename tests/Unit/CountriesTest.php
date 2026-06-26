<?php

declare(strict_types=1);

namespace Test\TinyBlocks\Country\Unit;

use PHPUnit\Framework\TestCase;
use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Countries;
use TinyBlocks\Country\Country;

final class CountriesTest extends TestCase
{
    public function testAllReturnsEveryCountry(): void
    {
        /** @Given the collection of every country */
        $countries = Countries::all();

        /** @When counting the collection */
        $count = $countries->count();

        /** @Then it contains every ISO 3166-1 country */
        self::assertSame(count(Alpha2Code::cases()), $count);
    }

    public function testAllEntriesAreCountryInstances(): void
    {
        /** @Given the collection of every country */
        $countries = Countries::all();

        /** @When fetching the first entry */
        $first = $countries->first();

        /** @Then it is a Country instance */
        self::assertInstanceOf(Country::class, $first);
    }
}
