<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

/**
 * Defines the contract for classes representing country codes per ISO 3166-1.
 *
 * Implementations are expected to be PHP backed enums; the `name` property is satisfied automatically
 * by the enum case identifier.
 *
 * @see https://www.iso.org/iso-3166-country-codes.html
 */
interface CountryCode
{
    /**
     * Returns the country code as its string value.
     *
     * @return string The country code value (e.g. 'BR' or 'US', 'BRA' or 'USA', '076' or '840').
     */
    public function toString(): string;
}
