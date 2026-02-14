<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

use Countable;
use TinyBlocks\Country\Internal\TimezoneCatalog;

/**
 * Immutable collection of Timezone objects for a country.
 *
 * Built from PHP's ICU/IANA timezone database — the authoritative source for timezone data.
 * The first element is considered the default/primary timezone for the country.
 */
final readonly class Timezones implements Countable
{
    /**
     * @param list<Timezone> $items All timezone objects for the country.
     * @param Timezone $default The default/primary timezone (first in the IANA list, or UTC as fallback).
     */
    private function __construct(private array $items, private Timezone $default)
    {
    }

    /**
     * Creates a Timezones collection from an Alpha-2 country code.
     *
     * @param Alpha2Code $alpha2 The two-letter country code.
     * @return Timezones The timezones collection for the given country.
     */
    public static function fromAlpha2(Alpha2Code $alpha2): Timezones
    {
        $items = array_map(
            static fn(string $id): Timezone => Timezone::from(identifier: $id),
            TimezoneCatalog::forAlpha2(alpha2Value: $alpha2->value)
        );

        return new Timezones(items: $items, default: $items[0] ?? Timezone::utc());
    }

    /**
     * Returns all Timezone objects for this country.
     *
     * @return list<Timezone> The list of all timezone objects.
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Returns the number of timezones for this country.
     *
     * @return int The total number of timezone identifiers.
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Returns the default (primary) Timezone for this country.
     *
     * The first identifier returned by the IANA database is used as the default.
     * Falls back to UTC when no timezone is available.
     *
     * @return Timezone The default timezone for the country.
     */
    public function default(): Timezone
    {
        return $this->default;
    }

    /**
     * Checks whether the given IANA identifier belongs to this country's timezones.
     *
     * @param string $iana The IANA timezone identifier to check (e.g. America/New_York).
     * @return bool True if the identifier belongs to this country, false otherwise.
     */
    public function contains(string $iana): bool
    {
        return array_any(
            $this->items,
            static fn(Timezone $timezone): bool => $timezone->value === $iana
        );
    }

    /**
     * Finds a Timezone by its IANA identifier.
     *
     * @param string $iana The IANA timezone identifier to search for (e.g. America/Sao_Paulo).
     * @return Timezone The matching Timezone, or UTC if not found in this country.
     */
    public function findByIdentifier(string $iana): Timezone
    {
        return array_find(
            $this->items,
            static fn(Timezone $timezone): bool => $timezone->value === $iana
        ) ?? Timezone::utc();
    }

    /**
     * Returns all timezone identifiers as plain strings.
     *
     * @return list<string> The list of IANA timezone identifier strings.
     */
    public function toStrings(): array
    {
        return array_map(
            static fn(Timezone $timezone): string => $timezone->toString(),
            $this->items
        );
    }
}
