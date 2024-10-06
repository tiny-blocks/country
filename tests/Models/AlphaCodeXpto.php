<?php

namespace TinyBlocks\Country\Models;

use TinyBlocks\Country\Internal\AlphaCode;
use TinyBlocks\Country\Internal\AlphaCodeMapper;

enum AlphaCodeXpto: string implements AlphaCode
{
    use AlphaCodeMapper;

    case SWITZERLAND = 'CH';
}
