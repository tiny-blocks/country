<?php

namespace TinyBlocks\Country\Internal;

use BackedEnum;

trait AlphaCodeAdapter
{
    public function getBy(string $name, array $inCases): BackedEnum
    {
        $alphaCodes = [];

        foreach ($inCases as $alphaCode) {
            if ($alphaCode->name == $name) {
                $alphaCodes[$name] = $alphaCode;
            }
        }

        return $alphaCodes[$name];
    }

    public function getName(): string
    {
        return $this->name;
    }
}
