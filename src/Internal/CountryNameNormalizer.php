<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Internal;

final readonly class CountryNameNormalizer
{
    private const array LOWERCASE_WORDS = ['Of', 'And', 'The'];

    public static function fromEnumName(string $enumName): string
    {
        $replaced = str_replace('_', ' ', $enumName);
        $titleCase = mb_convert_case($replaced, MB_CASE_TITLE);

        return str_replace(
            self::LOWERCASE_WORDS,
            array_map(strtolower(...), self::LOWERCASE_WORDS),
            $titleCase
        );
    }
}
