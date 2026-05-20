<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

use TinyBlocks\Country\Exceptions\InvalidCountryCode;
use TinyBlocks\Country\Exceptions\InvalidCountryCodeImplementation;
use TinyBlocks\Country\Internal\CountryNameNormalizer;
use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectBehavior;

/**
 * Country represented by ISO 3166-1 alpha and numeric codes and IANA timezones.
 *
 * Carries the country name, both alpha codes, the numeric code, and all IANA timezones for the country.
 */
class Country implements ValueObject
{
    use ValueObjectBehavior;

    private const int ALPHA2_CODE_LENGTH = 2;
    private const int NUMERIC_CODE_LENGTH = 3;

    protected function __construct(
        public readonly string $name,
        public readonly Alpha2Code $alpha2,
        public readonly Alpha3Code $alpha3,
        public readonly NumericCode $numeric,
        public readonly CountryTimezones $timezones
    ) {
    }

    /**
     * Creates a Country from a CountryCode instance.
     *
     * @param CountryCode $code An Alpha2Code, Alpha3Code, or NumericCode instance.
     * @param string|null $name Optional custom country name. If null or empty, the name is derived from the enum case.
     * @return static The created Country instance.
     * @throws InvalidCountryCodeImplementation If the CountryCode is not Alpha2Code, Alpha3Code, or NumericCode.
     */
    public static function from(CountryCode $code, ?string $name = null): static
    {
        [$alpha2, $alpha3, $numeric] = match (true) {
            $code instanceof Alpha2Code  => [$code, $code->toAlpha3(), $code->toNumeric()],
            $code instanceof Alpha3Code  => [$code->toAlpha2(), $code, $code->toNumeric()],
            $code instanceof NumericCode => [$code->toAlpha2(), $code->toAlpha3(), $code],
            default                      => throw new InvalidCountryCodeImplementation(class: $code::class)
        };

        $resolvedName = empty($name)
            ? CountryNameNormalizer::fromEnumName(enumName: $alpha2->name)
            : $name;

        return new static(
            name: $resolvedName,
            alpha2: $alpha2,
            alpha3: $alpha3,
            numeric: $numeric,
            timezones: CountryTimezones::fromAlpha2(alpha2: $alpha2)
        );
    }

    /**
     * Creates a Country from a string country code (Alpha-2, Alpha-3, or numeric).
     *
     * @param string $code The country code string (e.g. 'BR' or 'US', 'BRA' or 'USA', '076' or '840').
     * @param string|null $name Optional custom country name.
     * @return static The created Country instance.
     * @throws InvalidCountryCode If the string does not match any known Alpha-2, Alpha-3, or numeric code.
     */
    public static function fromString(string $code, ?string $name = null): static
    {
        $length = strlen($code);

        $resolved = match ($length) {
            self::ALPHA2_CODE_LENGTH  => Alpha2Code::tryFrom($code),
            self::NUMERIC_CODE_LENGTH => ctype_digit($code) ? NumericCode::tryFrom($code) : Alpha3Code::tryFrom($code),
            default                   => null
        };

        if (is_null($resolved)) {
            throw new InvalidCountryCode(code: $code);
        }

        return static::from(code: $resolved, name: $name);
    }

    /**
     * Tries to create a Country from a string country code; returns null if the code is invalid.
     *
     * @param string $code The country code string (e.g. 'BR' or 'US', 'BRA' or 'USA', '076' or '840').
     * @param string|null $name Optional custom country name.
     * @return static|null The created Country instance, or null if the code does not match any known country code.
     */
    public static function tryFromString(string $code, ?string $name = null): ?static
    {
        try {
            return static::fromString(code: $code, name: $name);
        } catch (InvalidCountryCode) {
            return null;
        }
    }
}
