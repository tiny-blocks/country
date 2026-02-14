<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Internal;

use DateTimeZone;

/**
 * Builds and caches the mapping from Alpha-2 country codes to their IANA timezone identifiers.
 *
 * Uses PHP's ICU/IANA timezone database as the authoritative source.
 */
final readonly class TimezoneCatalog
{
    /**
     * Returns all IANA timezone identifiers for a given Alpha-2 country code.
     *
     * @param string $alpha2Value The two-letter country code (e.g. 'US', 'BR').
     * @return list<string> The IANA timezone identifiers, or an empty array if none found.
     */
    public static function forAlpha2(string $alpha2Value): array
    {
        /** @var array<string, list<string>> $catalog */
        static $catalog = [];

        return $catalog[$alpha2Value] ??= DateTimeZone::listIdentifiers(
            timezoneGroup: DateTimeZone::PER_COUNTRY,
            countryCode: $alpha2Value
        );
    }
}
