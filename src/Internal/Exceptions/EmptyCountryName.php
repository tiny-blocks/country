<?php

namespace TinyBlocks\Country\Internal\Exceptions;

use RuntimeException;

final class EmptyCountryName extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Country name cannot be empty.');
    }
}
