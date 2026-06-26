<?php

declare(strict_types=1);

namespace Test\TinyBlocks\Country\Models;

use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Country;

final class CountryWithCallingCode extends Country
{
    public function callingCode(): string
    {
        return match ($this->alpha2()) {
            Alpha2Code::BRAZIL => '+55',
            default            => '+1'
        };
    }
}
