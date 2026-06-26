<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

/**
 * Contract for an ISO 3166-1 country code in any of its three representations.
 *
 * <p>Implementations are PHP backed enums. Each one converts to the other two representations
 * and to its string value, so a consumer can treat any code polymorphically.</p>
 *
 * @see https://www.iso.org/iso-3166-country-codes.html
 */
interface CountryCode
{
    /**
     * Returns the equivalent Alpha-2 code.
     *
     * @return Alpha2Code The Alpha-2 code for the same country.
     */
    public function toAlpha2(): Alpha2Code;

    /**
     * Returns the equivalent Alpha-3 code.
     *
     * @return Alpha3Code The Alpha-3 code for the same country.
     */
    public function toAlpha3(): Alpha3Code;

    /**
     * Returns the code as its string value.
     *
     * @return string The code value (e.g. 'BR', 'BRA', or '076').
     */
    public function toString(): string;

    /**
     * Returns the equivalent numeric code.
     *
     * @return NumericCode The numeric code for the same country.
     */
    public function toNumeric(): NumericCode;
}
