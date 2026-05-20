<?php

declare(strict_types=1);

namespace Test\TinyBlocks\Country\Models;

use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Alpha3Code;
use TinyBlocks\Country\Country;
use TinyBlocks\Country\CountryCode;
use TinyBlocks\Country\CountryTimezones;
use TinyBlocks\Country\NumericCode;

final class CountryWithCallingCode extends Country
{
    protected function __construct(
        string $name,
        Alpha2Code $alpha2,
        Alpha3Code $alpha3,
        NumericCode $numeric,
        CountryTimezones $timezones,
        public readonly string $callingCode
    ) {
        parent::__construct(
            name: $name,
            alpha2: $alpha2,
            alpha3: $alpha3,
            numeric: $numeric,
            timezones: $timezones
        );
    }

    public static function withCallingCode(CountryCode $code, string $callingCode): CountryWithCallingCode
    {
        $base = Country::from(code: $code);

        return new CountryWithCallingCode(
            name: $base->name,
            alpha2: $base->alpha2,
            alpha3: $base->alpha3,
            numeric: $base->numeric,
            timezones: $base->timezones,
            callingCode: $callingCode
        );
    }
}
