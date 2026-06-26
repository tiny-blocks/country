<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

use TinyBlocks\Collection\Collection;

/**
 * Collection of every ISO 3166-1 country, resolved on demand.
 *
 * @extends Collection<Country>
 */
final class Countries extends Collection
{
    /**
     * Creates a Countries collection with every ISO 3166-1 country.
     *
     * @return Countries Every country.
     */
    public static function all(): Countries
    {
        $countries = array_map(
            static fn(Alpha2Code $code): Country => Country::from(code: $code),
            Alpha2Code::cases()
        );

        return Countries::createFrom(elements: $countries);
    }
}
