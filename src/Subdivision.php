<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

use TinyBlocks\Country\Exceptions\InvalidSubdivisionCode;
use TinyBlocks\Country\Internal\SubdivisionData;
use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectBehavior;

/**
 * Subdivision of a country identified by its ISO 3166-2 code.
 *
 * <p>A lazy handle that stores only the code. The name, the category, and the owning country are
 * resolved on demand. Equality is by the code alone.</p>
 *
 * @see https://www.iso.org/glossary-for-iso-3166.html
 */
final readonly class Subdivision implements ValueObject
{
    use ValueObjectBehavior;

    private function __construct(private string $code)
    {
    }

    /**
     * Creates a Subdivision from an ISO 3166-2 code.
     *
     * @param string $code The subdivision code (e.g. 'BR-SP').
     * @return Subdivision The created Subdivision.
     * @throws InvalidSubdivisionCode If the code matches no known subdivision.
     */
    public static function fromString(string $code): Subdivision
    {
        if (!SubdivisionData::exists(code: $code)) {
            throw new InvalidSubdivisionCode(code: $code);
        }

        return new Subdivision(code: $code);
    }

    /**
     * Creates a Subdivision from an ISO 3166-2 code, or null when the code is unknown.
     *
     * @param string $code The subdivision code (e.g. 'BR-SP').
     * @return Subdivision|null The created Subdivision, or null when the code matches nothing.
     */
    public static function tryFromString(string $code): ?Subdivision
    {
        try {
            return Subdivision::fromString(code: $code);
        } catch (InvalidSubdivisionCode) {
            return null;
        }
    }

    /**
     * Returns the code.
     *
     * @return string The ISO 3166-2 code (e.g. 'BR-SP').
     */
    public function code(): string
    {
        return $this->code;
    }

    /**
     * Returns the name.
     *
     * @return string The subdivision name (e.g. 'São Paulo').
     */
    public function name(): string
    {
        return SubdivisionData::nameOf(code: $this->code);
    }

    /**
     * Returns the country this subdivision belongs to.
     *
     * @return Country The owning country, derived from the code prefix.
     */
    public function country(): Country
    {
        return Country::from(code: $this->countryCode());
    }

    /**
     * Returns the category.
     *
     * @return SubdivisionCategory The subdivision category (the ISO type).
     */
    public function category(): SubdivisionCategory
    {
        return SubdivisionData::categoryOf(code: $this->code);
    }

    /**
     * Tells whether this subdivision belongs to the given country.
     *
     * @param Country $country The country to test ownership against.
     * @return bool True when the subdivision's owning country equals the given country.
     */
    public function belongsTo(Country $country): bool
    {
        return $this->country()->equals(other: $country);
    }

    /**
     * Returns the Alpha-2 code of the country this subdivision belongs to.
     *
     * @return Alpha2Code The owning country's Alpha-2 code, derived from the code prefix.
     */
    public function countryCode(): Alpha2Code
    {
        [$prefix] = explode('-', $this->code);

        return Alpha2Code::from($prefix);
    }
}
