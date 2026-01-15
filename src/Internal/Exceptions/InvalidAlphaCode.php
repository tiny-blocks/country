<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Internal\Exceptions;

use RuntimeException;

final class InvalidAlphaCode extends RuntimeException
{
    public function __construct(private readonly string $alphaCode)
    {
        $template = 'Alpha code <%s> is invalid.';

        parent::__construct(message: sprintf($template, $this->alphaCode));
    }
}
