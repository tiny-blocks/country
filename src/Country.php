<?php

namespace TinyBlocks\Country;

use TinyBlocks\Country\Internal\AlphaCode;
use TinyBlocks\Country\Internal\Exceptions\InvalidAlphaCode;
use TinyBlocks\Country\Internal\Exceptions\InvalidAlphaCodeImplementation;
use TinyBlocks\Country\Internal\Name;
use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectAdapter;

final class Country implements ValueObject
{
    use ValueObjectAdapter;

    private function __construct(
        public readonly string $name,
        public readonly Alpha2Code $alpha2,
        public readonly Alpha3Code $alpha3
    ) {
    }

    public static function from(string|AlphaCode $alphaCode, string|null $name = null): Country
    {
        if (is_string($alphaCode)) {
            $alphaCodeFrom = strlen($alphaCode) === 2
                ? Alpha2Code::tryFrom(value: $alphaCode)
                : Alpha3Code::tryFrom(value: $alphaCode);

            if (is_null($alphaCodeFrom)) {
                throw new InvalidAlphaCode(alphaCode: $alphaCode);
            }

            return self::from(alphaCode: $alphaCodeFrom, name: $name);
        }

        $name = empty($name)
            ? Name::fromAlphaCode(alphaCode: $alphaCode)->value
            : Name::from(name: $name)->value;

        if ($alphaCode instanceof Alpha2Code) {
            return new Country(name: $name, alpha2: $alphaCode, alpha3: $alphaCode->toAlpha3());
        }

        if ($alphaCode instanceof Alpha3Code) {
            return new Country(name: $name, alpha2: $alphaCode->toAlpha2(), alpha3: $alphaCode);
        }

        throw new InvalidAlphaCodeImplementation(class: $alphaCode::class);
    }
}
