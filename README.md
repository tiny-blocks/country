# Country

[![License](https://img.shields.io/badge/license-MIT-green)](https://github.com/tiny-blocks/country/blob/main/LICENSE)

* [Overview](#overview)
* [Installation](#installation)
* [How to use](#how-to-use)
    + [Alpha2Code](#alpha2code)
    + [Alpha3Code](#alpha3code)
    + [NumericCode](#numericcode)
    + [Country](#country-1)
        - [Creating from objects](#creating-from-objects)
        - [Creating from string](#creating-from-string)
        - [Creating from string with safe fallback](#creating-from-string-with-safe-fallback)
    + [Timezones](#timezones)
        - [Getting all timezones](#getting-all-timezones)
        - [Getting the default timezone](#getting-the-default-timezone)
        - [Finding a timezone by identifier with UTC fallback](#finding-a-timezone-by-identifier-with-utc-fallback)
        - [Checking if a timezone belongs to the country](#checking-if-a-timezone-belongs-to-the-country)
* [License](#license)
* [Contributing](#contributing)

## Overview

Provides an [ISO 3166-1](https://www.iso.org/iso-3166-country-codes.html) country value object for PHP, carrying
Alpha-2,
Alpha-3, and numeric codes along with all [IANA timezones](https://www.iana.org) associated with the country. Supports
construction from any of the three code variants and automatic conversion between them. Built on top of the tiny-blocks
value-object contract to guarantee immutability and structural equality.

## Installation

```bash
composer require tiny-blocks/country
```

## How to use

The library exposes country codes according to ISO 3166-1 specifications. It is possible to create a representation
of a country that groups the codes, its name, and all its IANA timezones.

### Alpha2Code

A two-letter code that represents a country name, recommended as the general purpose code.

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Alpha2Code;

$alpha2Code = Alpha2Code::BRAZIL;

$alpha2Code->name;               # BRAZIL
$alpha2Code->value;              # BR
$alpha2Code->toAlpha3()->value;  # BRA
$alpha2Code->toNumeric()->value; # 076
```

### Alpha3Code

A three-letter code that represents a country name, which is usually more closely related to the country name.

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Alpha3Code;

$alpha3Code = Alpha3Code::UNITED_STATES_OF_AMERICA;

$alpha3Code->name;               # UNITED_STATES_OF_AMERICA
$alpha3Code->value;              # USA
$alpha3Code->toAlpha2()->value;  # US
$alpha3Code->toNumeric()->value; # 840
```

### NumericCode

A three-digit numeric code that represents a country name, defined by ISO 3166-1. Stored as a string to preserve the
leading zeros published by ISO (e.g. `'004'` for Afghanistan).

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\NumericCode;

$numericCode = NumericCode::BRAZIL;

$numericCode->name;              # BRAZIL
$numericCode->value;             # 076
$numericCode->toInteger();       # 76
$numericCode->toAlpha2()->value; # BR
$numericCode->toAlpha3()->value; # BRA
```

### Country

A `Country` instance can be created using an `Alpha-2`, `Alpha-3`, or numeric code, along with an optional country name.
There are two main methods to create a `Country` object: `from` (which accepts objects) and `fromString` (which accepts
strings).

Each `Country` automatically carries all its IANA timezones.

#### Creating from objects

You can create a `Country` instance using the `from` method by providing an `Alpha2Code`, `Alpha3Code`, or `NumericCode`
object. Optionally, you can pass the name of the country. If no name is provided, the default is the English version of
the country name.

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Country;

$country = Country::from(code: Alpha2Code::BRAZIL);

$country->name;           # Brazil
$country->alpha2->value;  # BR
$country->alpha3->value;  # BRA
$country->numeric->value; # 076
```

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Alpha3Code;
use TinyBlocks\Country\Country;

$country = Country::from(code: Alpha3Code::UNITED_STATES_OF_AMERICA);

$country->name;           # United States of America
$country->alpha2->value;  # US
$country->alpha3->value;  # USA
$country->numeric->value; # 840
```

If you want to specify a custom name:

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Country;

$country = Country::from(code: Alpha2Code::BRAZIL, name: 'Brasil');

$country->name;           # Brasil
$country->alpha2->value;  # BR
$country->alpha3->value;  # BRA
$country->numeric->value; # 076
```

You can also create a `Country` from a numeric code:

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Country;
use TinyBlocks\Country\NumericCode;

$country = Country::from(code: NumericCode::UNITED_STATES_OF_AMERICA);

$country->name;           # United States of America
$country->alpha2->value;  # US
$country->alpha3->value;  # USA
$country->numeric->value; # 840
```

#### Creating from string

Alternatively, you can create a `Country` instance using the `fromString` method, which accepts an `Alpha-2`, `Alpha-3`,
or numeric code as a string.

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Country;

$country = Country::fromString(code: 'BR');

$country->name;           # Brazil
$country->alpha2->value;  # BR
$country->alpha3->value;  # BRA
$country->numeric->value; # 076
```

You can also pass a custom country name:

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Country;

$country = Country::fromString(code: 'USA', name: 'United States');

$country->name;           # United States
$country->alpha2->value;  # US
$country->alpha3->value;  # USA
$country->numeric->value; # 840
```

The string form also accepts the numeric code:

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Country;

$country = Country::fromString(code: '076');

$country->name;           # Brazil
$country->alpha2->value;  # BR
$country->alpha3->value;  # BRA
$country->numeric->value; # 076
```

#### Creating from string with safe fallback

Use `tryFromString` when you want to validate an external input without exception handling. It returns
`null` if the code does not match any known Alpha-2, Alpha-3, or numeric code.

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Country;

$valid = Country::tryFromString(code: 'BR');
$valid?->alpha2->value;   # BR

$invalid = Country::tryFromString(code: 'XYZ');
$invalid;                 # null
```

### Timezones

Every `Country` includes an immutable `CountryTimezones` collection, built from the IANA timezone database (via PHP's
ICU integration).

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Country;

$country = Country::from(code: Alpha2Code::BRAZIL);

$country->timezones->count();      # 4
$country->timezones->default();    # Timezone("America/Noronha")
$country->timezones->toStrings();  # ["America/Noronha", "America/Belem", "America/Sao_Paulo", ...]
```

#### Getting all timezones

Returns all `Timezone` objects for the country:

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Country;

$country = Country::from(code: Alpha2Code::BRAZIL);

$country->timezones->all(); # [Timezone("America/Noronha"), Timezone("America/Belem"), ...]
```

#### Getting the default timezone

Returns the primary timezone (first in the IANA list). Falls back to `UTC` for territories without an assigned timezone:

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Country;

$country = Country::from(code: Alpha2Code::BRAZIL);
$country->timezones->default(); # Timezone("America/Noronha")

# Bouvet Island has no IANA timezones, so the default falls back to UTC.
$country = Country::from(code: Alpha2Code::BOUVET_ISLAND);
$country->timezones->default(); # Timezone("UTC")
```

#### Finding a timezone by identifier with UTC fallback

Searches for a specific IANA identifier within the country's timezones. Returns UTC if not found.

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Country;

$country = Country::from(code: Alpha2Code::UNITED_STATES_OF_AMERICA);

$country->timezones->findByIdentifierOrUtc(iana: 'America/New_York'); # Timezone("America/New_York")
$country->timezones->findByIdentifierOrUtc(iana: 'Asia/Tokyo');       # Timezone("UTC")
```

#### Checking if a timezone belongs to the country

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Country;

$country = Country::from(code: Alpha2Code::BRAZIL);

$country->timezones->contains(iana: 'America/Sao_Paulo'); # true
$country->timezones->contains(iana: 'America/New_York');  # false
```

## License

Country is licensed under [MIT](LICENSE).

## Contributing

Please follow the [contributing guidelines](https://github.com/tiny-blocks/tiny-blocks/blob/main/CONTRIBUTING.md) to
contribute to the project.
