<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

use TinyBlocks\Country\Internal\CountryNameNormalizer;
use TinyBlocks\Country\Internal\Exceptions\InvalidAlphaCode;
use TinyBlocks\Country\Internal\Exceptions\InvalidAlphaCodeImplementation;
use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectBehavior;

/**
 * Value Object representing a country using ISO-3166 specifications.
 *
 * Carries the country name, both alpha codes, and all IANA timezones for the country.
 */
class Country implements ValueObject
{
    use ValueObjectBehavior;

    private const int ALPHA2_CODE_LENGTH = 2;

    private function __construct(
        public readonly string $name,
        public readonly Alpha2Code $alpha2,
        public readonly Alpha3Code $alpha3,
        public readonly Timezones $timezones
    ) {
    }

    /**
     * Creates a Country from an AlphaCode instance.
     *
     * @param AlphaCode $alphaCode An Alpha2Code or Alpha3Code instance.
     * @param string|null $name Optional custom country name. If null or empty, the name is derived from the enum case.
     * @return static The created Country instance.
     * @throws InvalidAlphaCodeImplementation If the AlphaCode is not Alpha2Code or Alpha3Code.
     */
    public static function from(AlphaCode $alphaCode, ?string $name = null): static
    {
        $resolvedName = empty($name)
            ? CountryNameNormalizer::fromEnumName(enumName: $alphaCode->getName())
            : $name;

        $resolveAlphaPair = static fn(): array => match (true) {
            $alphaCode instanceof Alpha2Code => [$alphaCode, $alphaCode->toAlpha3()],
            $alphaCode instanceof Alpha3Code => [$alphaCode->toAlpha2(), $alphaCode],
            default                          => throw new InvalidAlphaCodeImplementation(class: $alphaCode::class)
        };

        [$alpha2, $alpha3] = $resolveAlphaPair();

        return new static(
            name: $resolvedName,
            alpha2: $alpha2,
            alpha3: $alpha3,
            timezones: Timezones::fromAlpha2(alpha2: $alpha2)
        );
    }

    /**
     * Creates a Country from a string alpha code (Alpha-2 or Alpha-3).
     *
     * @param string $alphaCode The alpha code string (e.g. 'US' or 'USA').
     * @param string|null $name Optional custom country name.
     * @return static The created Country instance.
     * @throws InvalidAlphaCode If the string does not match any known Alpha-2 or Alpha-3 code.
     */
    public static function fromString(string $alphaCode, ?string $name = null): static
    {
        $resolved = strlen($alphaCode) === self::ALPHA2_CODE_LENGTH
            ? Alpha2Code::tryFrom(value: $alphaCode)
            : Alpha3Code::tryFrom(value: $alphaCode);

        if (is_null($resolved)) {
            throw new InvalidAlphaCode(alphaCode: $alphaCode);
        }

        return static::from(alphaCode: $resolved, name: $name);
    }
}
