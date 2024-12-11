<?php

declare(strict_types=1);

namespace TinyBlocks\Country\Models;

use TinyBlocks\Country\AlphaCode;
use TinyBlocks\Country\Internal\AlphaCodeMapper;

enum AlphaCodeXpto: string implements AlphaCode
{
    use AlphaCodeMapper;

    case SWITZERLAND = 'CH';

    public function getName(): string
    {
        return $this->value;
    }
}
