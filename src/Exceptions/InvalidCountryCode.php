<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Exceptions;

use DomainException;

final class InvalidCountryCode extends DomainException
{
    public function __construct(string $code)
    {
        $template = 'Country code <%s> is invalid.';

        parent::__construct(message: sprintf($template, $code));
    }
}
