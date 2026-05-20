<?php

declare(strict_types=1);

namespace Test\TinyBlocks\Country\Models;

use TinyBlocks\Country\CountryCode;

enum CountryCodeXpto: string implements CountryCode
{
    case SWITZERLAND = 'CH';

    public function toString(): string
    {
        return $this->value;
    }
}
