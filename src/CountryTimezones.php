<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

use Countable;
use DateTimeZone;
use TinyBlocks\Time\Timezone;
use TinyBlocks\Time\Timezones;
use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectBehavior;

/**
 * Collection of Timezone objects for a country.
 *
 * Built from PHP's ICU/IANA timezone database, the authoritative source for timezone data.
 * The first entry is the default (primary) timezone for the country.
 */
final readonly class CountryTimezones implements ValueObject, Countable
{
    use ValueObjectBehavior;

    private function __construct(private Timezone $default, private Timezones $timezones)
    {
    }

    /**
     * Creates a CountryTimezones from an Alpha-2 country code.
     *
     * @param Alpha2Code $alpha2 The Alpha-2 country code (e.g. "US" for United States).
     * @return CountryTimezones A new instance containing the timezones for the specified country.
     */
    public static function fromAlpha2(Alpha2Code $alpha2): CountryTimezones
    {
        $identifiers = DateTimeZone::listIdentifiers(
            timezoneGroup: DateTimeZone::PER_COUNTRY,
            countryCode: $alpha2->value
        );
        $timezones = Timezones::fromStrings(...$identifiers);
        $default = $timezones->all()[0] ?? Timezone::utc();

        return new CountryTimezones(default: $default, timezones: $timezones);
    }

    /**
     * Returns all Timezone objects for this country.
     *
     * @return list<Timezone> The list of all timezone objects.
     */
    public function all(): array
    {
        return $this->timezones->all();
    }

    /**
     * Returns the number of timezones for this country.
     *
     * @return int The total number of timezone identifiers.
     */
    public function count(): int
    {
        return $this->timezones->count();
    }

    /**
     * Returns the default (primary) Timezone for this country.
     *
     * The first identifier returned by the IANA database is used as the default. Falls back to
     * UTC when no timezone is available.
     *
     * @return Timezone The default timezone for the country.
     */
    public function default(): Timezone
    {
        return $this->default;
    }

    /**
     * Tells whether the given IANA identifier belongs to this country's timezones.
     *
     * @param string $iana The IANA timezone identifier to check (e.g. America/New_York).
     * @return bool True if the identifier belongs to this country, false otherwise.
     */
    public function contains(string $iana): bool
    {
        return $this->timezones->contains(iana: $iana);
    }

    /**
     * Finds a Timezone object by its IANA identifier.
     *
     * @param string $iana The IANA timezone identifier to find (e.g. America/New_York).
     * @return Timezone The corresponding Timezone object if found, or UTC if not found.
     */
    public function findByIdentifierOrUtc(string $iana): Timezone
    {
        return $this->timezones->findByIdentifierOrUtc(iana: $iana);
    }

    /**
     * Returns all timezone identifiers as plain strings.
     *
     * @return list<string> The list of IANA timezone identifier strings.
     */
    public function toStrings(): array
    {
        return $this->timezones->toStrings();
    }
}
