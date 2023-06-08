<?php

namespace TinyBlocks\Country\Internal\Exceptions;

use RuntimeException;

final class InvalidAlphaCode extends RuntimeException
{
    public function __construct(string $alphaCode)
    {
        $template = 'Alpha code <%s> is invalid.';
        parent::__construct(message: sprintf($template, $alphaCode));
    }
}
