<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Internal;

use BackedEnum;
use TinyBlocks\Country\Internal\Exceptions\AlphaCodeNotFound;

interface AlphaCode extends BackedEnum
{
    /**
     * Retrieve an AlphaCode enum case by its name.
     *
     * @param string $name The name of the AlphaCode case to search for.
     * @param array $alphaCodes An array of possible enum cases to search within.
     * @return BackedEnum The matching AlphaCode enum case.
     * @throws AlphaCodeNotFound If the AlphaCode case with the given name is not found.
     */
    public function getBy(string $name, array $alphaCodes): BackedEnum;
}
