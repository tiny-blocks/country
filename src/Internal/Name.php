<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Internal;

use TinyBlocks\Country\Internal\Exceptions\EmptyCountryName;

final readonly class Name
{
    private array $prepositions;

    private function __construct(public string $value)
    {
        if (empty($this->value)) {
            throw new EmptyCountryName();
        }

        $this->prepositions = ['Of', 'And', 'The'];
    }

    public static function from(string $name): Name
    {
        return new Name(value: $name);
    }

    public static function fromAlphaCode(AlphaCode $alphaCode): Name
    {
        return self::from(name: $alphaCode->name)->normalizeName();
    }

    private function normalizeName(): Name
    {
        $subject = mb_convert_case($this->value, MB_CASE_TITLE);
        $normalized = str_replace('_', ' ', $subject);

        foreach ($this->prepositions as $word) {
            $normalized = str_replace($word, strtolower($word), $normalized);
        }

        return new Name(value: $normalized);
    }
}
