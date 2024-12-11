<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Internal;

use TinyBlocks\Country\AlphaCode;
use TinyBlocks\Country\Internal\Exceptions\EmptyCountryName;

final readonly class Name
{
    private const array PREPOSITIONS = ['Of', 'And', 'The'];

    private function __construct(public string $value)
    {
        if (empty($this->value)) {
            throw new EmptyCountryName();
        }
    }

    public static function from(string $name): Name
    {
        return new Name(value: $name);
    }

    public static function fromAlphaCode(AlphaCode $alphaCode): Name
    {
        return self::from(name: $alphaCode->getName())->normalizeName();
    }

    private function normalizeName(): Name
    {
        $subject = mb_convert_case($this->value, MB_CASE_TITLE);
        $normalized = str_replace('_', ' ', $subject);

        foreach (self::PREPOSITIONS as $word) {
            $normalized = str_replace($word, strtolower($word), $normalized);
        }

        return new Name(value: $normalized);
    }
}
