<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Internal;

use BackedEnum;
use TinyBlocks\Country\Internal\Exceptions\AlphaCodeNotFound;

trait AlphaCodeMapper
{
    public function getBy(string $name, array $alphaCodes): BackedEnum
    {
        foreach ($alphaCodes as $alphaCode) {
            if ($alphaCode->name === $name) {
                return $alphaCode;
            }
        }

        throw new AlphaCodeNotFound(name: $name);
    }
}
