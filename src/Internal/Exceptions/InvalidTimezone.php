<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Internal\Exceptions;

use InvalidArgumentException;

final class InvalidTimezone extends InvalidArgumentException
{
    public function __construct(private readonly string $identifier)
    {
        $template = 'Timezone <%s> is invalid.';

        parent::__construct(message: sprintf($template, $this->identifier));
    }
}
