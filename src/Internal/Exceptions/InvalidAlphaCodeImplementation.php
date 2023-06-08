<?php

namespace TinyBlocks\Country\Internal\Exceptions;

use RuntimeException;

final class InvalidAlphaCodeImplementation extends RuntimeException
{
    public function __construct(string $class)
    {
        $template = 'The alpha code implementation <%s> is invalid.';
        parent::__construct(message: sprintf($template, $class));
    }
}
