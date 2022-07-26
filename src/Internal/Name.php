<?php

namespace TinyBlocks\Country\Internal;

use TinyBlocks\Country\Internal\Exceptions\EmptyCountryName;

final class Name
{
    private static array $prepositions = ['Of', 'And', 'The'];

    private function __construct(public readonly string $value)
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
        $name = str_replace('_', ' ', $subject);

        foreach (self::$prepositions as $word) {
            $name = str_replace($word, mb_strtolower($word), $name);
        }

        return new Name(value: $name);
    }
}
