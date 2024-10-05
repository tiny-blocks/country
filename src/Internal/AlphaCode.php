<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Internal;

use BackedEnum;

interface AlphaCode
{
    /**
     * Retrieve an AlphaCode enum case by its name.
     *
     * @param string $name The name of the AlphaCode case to search for.
     * @param array $inCases An array of possible enum cases to search within.
     * @return BackedEnum The matching AlphaCode enum case.
     */
    public function getBy(string $name, array $inCases): BackedEnum;

    /**
     * Get the name of the AlphaCode.
     *
     * @return string The name of the AlphaCode.
     */
    public function getName(): string;
}
