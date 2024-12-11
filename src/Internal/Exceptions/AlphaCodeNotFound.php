<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Internal\Exceptions;

use RuntimeException;

final class AlphaCodeNotFound extends RuntimeException
{
    public function __construct(string $name)
    {
        $template = 'Alpha code with name <%s> not found.';

        parent::__construct(message: sprintf($template, $name));
    }
}
