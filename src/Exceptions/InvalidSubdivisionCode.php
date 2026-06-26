<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Exceptions;

use DomainException;

final class InvalidSubdivisionCode extends DomainException
{
    public function __construct(string $code)
    {
        $template = 'Subdivision code <%s> is invalid.';

        parent::__construct(message: sprintf($template, $code));
    }
}
