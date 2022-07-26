<?php

namespace TinyBlocks\Country\Internal;

use BackedEnum;

interface AlphaCode
{
    /**
     * Get AlphaCode by name.
     * @param string $name
     * @param array $inCases
     * @return BackedEnum
     */
    public function getBy(string $name, array $inCases): BackedEnum;

    /**
     * Get the AlphaCode name.
     * @return string
     */
    public function getName(): string;
}
