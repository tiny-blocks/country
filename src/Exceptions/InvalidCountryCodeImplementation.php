<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Exceptions;

use RuntimeException;

final class InvalidCountryCodeImplementation extends RuntimeException
{
    public function __construct(string $class)
    {
        $template = 'The country code implementation <%s> is invalid.';

        parent::__construct(message: sprintf($template, $class));
    }
}
