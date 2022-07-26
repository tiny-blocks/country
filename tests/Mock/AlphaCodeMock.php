<?php

namespace TinyBlocks\Country\Mock;

use TinyBlocks\Country\Internal\AlphaCode;
use TinyBlocks\Country\Internal\AlphaCodeAdapter;

enum AlphaCodeMock: string implements AlphaCode
{
    use AlphaCodeAdapter;

    case SWITZERLAND = 'CH';
}
