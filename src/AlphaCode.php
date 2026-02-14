<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

/**
 * Defines the contract for classes representing alpha codes of countries.
 *
 * @see https://www.iso.org/iso-3166-country-codes.html
 */
interface AlphaCode
{
    /**
     * Gets the enum case name associated with the alpha code (e.g. BRAZIL, UNITED_STATES_OF_AMERICA).
     * @return string The name of the enum case representing the alpha code.
     */
    public function getName(): string;
}
