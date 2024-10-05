<?php

namespace TinyBlocks\Country\Models;

use TinyBlocks\Country\Internal\AlphaCode;
use TinyBlocks\Country\Internal\AlphaCodeAdapter;

enum AlphaCodeXpto: string implements AlphaCode
{
    use AlphaCodeAdapter;

    case SWITZERLAND = 'CH';
}
