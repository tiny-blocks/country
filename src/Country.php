<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

use TinyBlocks\Country\Exceptions\InvalidCountryCode;
use TinyBlocks\Country\Internal\CountryData;
use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectBehavior;

/**
 * Country identified by its ISO 3166-1 code.
 *
 * <p>A lazy handle that stores only the Alpha-2 code. The name, the Alpha-3 and numeric codes, the
 * IANA timezones, and the ISO 3166-2 subdivisions are all resolved on demand. Equality is by the
 * Alpha-2 code alone.</p>
 *
 * @see https://www.iso.org/iso-3166-country-codes.html
 */
class Country implements ValueObject
{
    use ValueObjectBehavior;

    private const int ALPHA2_LENGTH = 2;
    private const int ALPHA3_LENGTH = 3;

    private function __construct(private readonly Alpha2Code $alpha2)
    {
    }

    /**
     * Creates a Country from a country code.
     *
     * @param CountryCode $code An Alpha2Code, Alpha3Code, or NumericCode.
     * @return static The created Country.
     */
    public static function from(CountryCode $code): static
    {
        return new static(alpha2: $code->toAlpha2());
    }

    /**
     * Creates a Country from a string code in Alpha-2, Alpha-3, or numeric form.
     *
     * @param string $code The country code string (e.g. 'BR', 'BRA', or '076').
     * @return static The created Country.
     * @throws InvalidCountryCode If the string matches no known Alpha-2, Alpha-3, or numeric code.
     */
    public static function fromString(string $code): static
    {
        $resolved = match (strlen($code)) {
            self::ALPHA2_LENGTH => Alpha2Code::tryFrom($code),
            self::ALPHA3_LENGTH => ctype_digit($code) ? NumericCode::tryFrom($code) : Alpha3Code::tryFrom($code),
            default => null
        };

        if (is_null($resolved)) {
            throw new InvalidCountryCode(code: $code);
        }

        return static::from(code: $resolved);
    }

    /**
     * Creates a Country from a string code, or null when the code is unknown.
     *
     * @param string $code The country code string (e.g. 'BR', 'BRA', or '076').
     * @return static|null The created Country, or null when the code matches nothing.
     */
    public static function tryFromString(string $code): ?static
    {
        try {
            return static::fromString(code: $code);
        } catch (InvalidCountryCode) {
            return null;
        }
    }

    /**
     * Returns the country name.
     *
     * @return string The ISO English short name (e.g. 'Brazil').
     */
    public function name(): string
    {
        return CountryData::nameOf(alpha2: $this->alpha2);
    }

    /**
     * Returns the Alpha-2 code.
     *
     * @return Alpha2Code The Alpha-2 code.
     */
    public function alpha2(): Alpha2Code
    {
        return $this->alpha2;
    }

    /**
     * Returns the Alpha-3 code.
     *
     * @return Alpha3Code The Alpha-3 code.
     */
    public function alpha3(): Alpha3Code
    {
        return $this->alpha2->toAlpha3();
    }

    /**
     * Returns the numeric code.
     *
     * @return NumericCode The numeric code.
     */
    public function numeric(): NumericCode
    {
        return $this->alpha2->toNumeric();
    }

    /**
     * Returns the country timezones.
     *
     * @return CountryTimezones The IANA timezones for the country.
     */
    public function timezones(): CountryTimezones
    {
        return CountryTimezones::fromAlpha2(alpha2: $this->alpha2);
    }

    /**
     * Returns the country subdivisions.
     *
     * @return Subdivisions The ISO 3166-2 subdivisions, possibly empty.
     */
    public function subdivisions(): Subdivisions
    {
        return CountryData::subdivisionsOf(alpha2: $this->alpha2);
    }
}
