<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

use TinyBlocks\Country\Internal\AlphaCode;
use TinyBlocks\Country\Internal\Exceptions\InvalidAlphaCode;
use TinyBlocks\Country\Internal\Exceptions\InvalidAlphaCodeImplementation;
use TinyBlocks\Country\Internal\Name;
use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectBehavior;

final class Country implements ValueObject
{
    use ValueObjectBehavior;

    private const ALPHA2_CODE_LENGTH = 2;

    private function __construct(public string $name, public Alpha2Code $alpha2, public Alpha3Code $alpha3)
    {
    }

    public static function from(AlphaCode $alphaCode, string|null $name = null): Country
    {
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

    public static function fromString(string $alphaCode, string|null $name = null): Country
    {
        $alphaCodeFrom = strlen($alphaCode) === self::ALPHA2_CODE_LENGTH
            ? Alpha2Code::tryFrom(value: $alphaCode)
            : Alpha3Code::tryFrom(value: $alphaCode);

        if (is_null($alphaCodeFrom)) {
            throw new InvalidAlphaCode(alphaCode: $alphaCode);
        }

        return self::from(alphaCode: $alphaCodeFrom, name: $name);
    }
}
