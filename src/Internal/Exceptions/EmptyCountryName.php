<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Internal\Exceptions;

use RuntimeException;

final class EmptyCountryName extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(message: 'Country name cannot be empty.');
    }
}
