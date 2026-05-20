<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Exceptions;

use RuntimeException;

final class InvalidCountryCode extends RuntimeException
{
    public function __construct(string $code)
    {
        $template = 'Country code <%s> is invalid.';

        parent::__construct(message: sprintf($template, $code));
    }
}
