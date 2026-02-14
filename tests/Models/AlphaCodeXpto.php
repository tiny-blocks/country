<?php

declare(strict_types=1);

namespace Test\TinyBlocks\Country\Models;

use TinyBlocks\Country\AlphaCode;

enum AlphaCodeXpto: string implements AlphaCode
{
    case SWITZERLAND = 'CH';

    public function getName(): string
    {
        return $this->value;
    }
}
