<?php

declare(strict_types=1);

namespace TinyBlocks\Country;

/**
 * Defines the contract for classes representing alpha codes of countries.
 */
interface AlphaCode
{
    /**
     * Gets the name associated with the alpha code.
     *
     * @return string The name of the country or region corresponding to the code.
     */
    public function getName(): string;
}
