# Country

[![License](https://img.shields.io/badge/license-MIT-green)](https://github.com/tiny-blocks/country/blob/main/LICENSE)

* [Overview](#overview)
* [Installation](#installation)
* [How to use](#how-to-use)
    + [Country codes](#country-codes)
    + [Country](#country)
        - [Creating from objects](#creating-from-objects)
        - [Creating from string](#creating-from-string)
        - [Safe creation](#safe-creation)
    + [Subdivisions](#subdivisions)
    + [All countries](#all-countries)
    + [Timezones](#timezones)
* [License](#license)
* [Contributing](#contributing)

## Overview

Provides [ISO 3166-1](https://www.iso.org/iso-3166-country-codes.html) country and
[ISO 3166-2](https://www.iso.org/glossary-for-iso-3166.html) subdivision value objects for PHP. A `Country` is a lazy
handle over an Alpha-2 code that resolves its name, its Alpha-3 and numeric codes, its
[IANA timezones](https://www.iana.org), and its subdivisions on demand. A `Subdivision` is the same kind of handle one
level down, resolving its name, its category, and its owning country from the ISO 3166-2 code.

Construction works from any of the three code variants (Alpha-2, Alpha-3, or numeric), with automatic conversion between
them. The data is generated from the ISO 3166 standard. Names are the official ISO English short names, so the values
are stable and citable rather than derived. The library builds on the tiny-blocks value-object contract for immutability
and structural equality, on tiny-blocks/time for timezones, and on tiny-blocks/collection for the country and
subdivision collections.

## Installation

```bash
composer require tiny-blocks/country
```

## How to use

### Country codes

Each country has three ISO 3166-1 representations: a two-letter `Alpha2Code`, a three-letter `Alpha3Code`, and a
three-digit `NumericCode`. Every code converts to the other two and to its string value.

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Alpha2Code;

$alpha2 = Alpha2Code::BRAZIL;

$alpha2->value;              # BR
$alpha2->toAlpha3()->value;  # BRA
$alpha2->toNumeric()->value; # 076
$alpha2->toString();         # BR
```

The numeric code keeps the ISO leading zeros as a string and exposes them as an integer through `toInteger`.

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\NumericCode;

$numeric = NumericCode::BRAZIL;

$numeric->value;             # 076
$numeric->toInteger();       # 76
$numeric->toAlpha2()->value; # BR
$numeric->toAlpha3()->value; # BRA
```

### Country

A `Country` can be created from any code object with `from`, or from a string with `fromString`. The name, both alpha
codes, the numeric code, the timezones, and the subdivisions are read through methods.

#### Creating from objects

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Country;

$country = Country::from(code: Alpha2Code::BRAZIL);

$country->name();           # Brazil
$country->alpha2()->value;  # BR
$country->alpha3()->value;  # BRA
$country->numeric()->value; # 076
```

The same country is produced from its Alpha-3 or numeric code, since `from` canonicalizes to the Alpha-2 code.

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Country;
use TinyBlocks\Country\NumericCode;

$country = Country::from(code: NumericCode::UNITED_STATES_OF_AMERICA);

$country->name();           # United States
$country->alpha2()->value;  # US
$country->alpha3()->value;  # USA
```

#### Creating from string

`fromString` accepts an Alpha-2, Alpha-3, or numeric code as a string. It throws `InvalidCountryCode` when the string
matches no known code.

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Country;

Country::fromString(code: 'BR')->name();   # Brazil
Country::fromString(code: 'USA')->name();  # United States
Country::fromString(code: '076')->name();  # Brazil
```

#### Safe creation

Use `tryFromString` to validate external input without handling exceptions. It returns `null` when the code matches no
known Alpha-2, Alpha-3, or numeric code.

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Country;

Country::tryFromString(code: 'BR')?->alpha2()->value; # BR
Country::tryFromString(code: 'XYZ');                  # null
```

### Subdivisions

Each `Country` exposes its ISO 3166-2 subdivisions as a `Subdivisions` collection, which may be empty for territories
that have none. A `Subdivision` can also be created directly from its code with `fromString` or `tryFromString`.

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Country;
use TinyBlocks\Country\Subdivision;

$subdivision = Subdivision::fromString(code: 'BR-SP');

$subdivision->code();                                              # BR-SP
$subdivision->name();                                              # São Paulo
$subdivision->category()->value;                                   # State
$subdivision->category()->isState();                               # true
$subdivision->country()->name();                                   # Brazil
$subdivision->belongsTo(country: Country::fromString(code: 'BR')); # true
```

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Country;

$subdivisions = Country::from(code: Alpha2Code::BRAZIL)->subdivisions();

$subdivisions->count();      # 27
$subdivisions->isEmpty();    # false
```

### All countries

`Countries::all` returns every ISO 3166-1 country as a collection of `Country` handles.

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Countries;

$countries = Countries::all();

$countries->count(); # 249
```

### Timezones

Every `Country` exposes a `CountryTimezones` collection, built from the IANA timezone database through PHP. The first
identifier is the default, and it falls back to `UTC` for territories without an assigned timezone.

```php
<?php

declare(strict_types=1);

use TinyBlocks\Country\Alpha2Code;
use TinyBlocks\Country\Country;

$timezones = Country::from(code: Alpha2Code::JAPAN)->timezones();

$timezones->count();                                 # 1
$timezones->default()->value;                        # Asia/Tokyo
$timezones->contains(iana: 'Asia/Tokyo');            # true
$timezones->contains(iana: 'America/New_York');      # false
$timezones->findByIdentifierOrUtc(iana: 'X')->value; # UTC
```

## License

Country is licensed under [MIT](LICENSE).

## Contributing

Please follow the [contributing guidelines](https://github.com/tiny-blocks/tiny-blocks/blob/main/CONTRIBUTING.md) to
contribute to the project.
