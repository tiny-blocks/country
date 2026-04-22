# Country

[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

* [Overview](#overview)
* [Installation](#installation)
* [How to use](#how-to-use)
    * [Alpha2Code](#alpha2code)
    * [Alpha3Code](#alpha3code)
    * [Country](#country-1)
    * [Timezones](#timezones)
* [License](#license)
* [Contributing](#contributing)

<div id='overview'></div>

## Overview

Provides an [ISO-3166](https://www.iso.org/iso-3166-country-codes.html) country value object for PHP, carrying Alpha-2
and Alpha-3 codes along with all [IANA timezones](https://www.iana.org) associated with the country. Supports
construction from either alpha
code variant and automatic conversion between them. Built on top of the tiny-blocks value-object contract to guarantee
immutability and structural equality.


<div id='installation'></div>

## Installation

```bash
composer require tiny-blocks/country
```

<div id='how-to-use'></div>

## How to use

The library exposes country codes according to ISO-3166 specifications. It is possible to create a representation
of a country that groups the codes, its name, and all its IANA timezones.

### Alpha2Code

A two-letter code that represents a country name, recommended as the general purpose code.

```php
use TinyBlocks\Country\Alpha2Code;

$alpha2Code = Alpha2Code::UNITED_STATES_OF_AMERICA;

$alpha2Code->name;              # UNITED_STATES_OF_AMERICA
$alpha2Code->value;             # US
$alpha2Code->toAlpha3()->value; # USA
```

### Alpha3Code

A three-letter code that represents a country name, which is usually more closely related to the country name.

```php
use TinyBlocks\Country\Alpha3Code;

$alpha3Code = Alpha3Code::UNITED_STATES_OF_AMERICA;

$alpha3Code->name;              # UNITED_STATES_OF_AMERICA
$alpha3Code->value;             # USA
$alpha3Code->toAlpha2()->value; # US
```

### Country

A `Country` instance can be created using either an `Alpha-2` or `Alpha-3` code, along with an optional country name.
There are two main methods to create a `Country` object: `from` (which accepts objects) and `fromString` (which accepts
strings).

Each `Country` automatically carries all its IANA timezones.

#### Creating from objects

You can create a `Country` instance using the `from` method by providing an `Alpha2Code` or `Alpha3Code` object.
Optionally, you can pass the name of the country. If no name is provided, the default is the English version of the
country name.

```php
use TinyBlocks\Country\Country;
use TinyBlocks\Country\Alpha2Code;

$country = Country::from(alphaCode: Alpha2Code::UNITED_STATES_OF_AMERICA);

$country->name;          # United States of America
$country->alpha2->value; # US
$country->alpha3->value; # USA
```

```php
use TinyBlocks\Country\Country;
use TinyBlocks\Country\Alpha3Code;

$country = Country::from(alphaCode: Alpha3Code::UNITED_STATES_OF_AMERICA);

$country->name;          # United States of America
$country->alpha2->value; # US
$country->alpha3->value; # USA
```

If you want to specify a custom name:

```php
use TinyBlocks\Country\Country;
use TinyBlocks\Country\Alpha3Code;

$country = Country::from(alphaCode: Alpha3Code::UNITED_STATES_OF_AMERICA, name: 'United States');

$country->name;          # United States
$country->alpha2->value; # US
$country->alpha3->value; # USA
```

#### Creating from string

Alternatively, you can create a `Country` instance using the `fromString` method, which accepts an `Alpha-2` or
`Alpha-3` code as a string.

```php
use TinyBlocks\Country\Country;

$country = Country::fromString(alphaCode: 'US');

$country->name;          # United States of America
$country->alpha2->value; # US
$country->alpha3->value; # USA
```

You can also pass a custom country name:

```php
use TinyBlocks\Country\Country;

$country = Country::fromString(alphaCode: 'USA', name: 'United States');

$country->name;          # United States
$country->alpha2->value; # US
$country->alpha3->value; # USA
```

### Timezones

Every `Country` includes an immutable `CountryTimezones` collection, built from the IANA timezone database (via PHP's
ICU integration).

```php
use TinyBlocks\Country\Country;
use TinyBlocks\Country\Alpha2Code;

$country = Country::from(alphaCode: Alpha2Code::BRAZIL);

$country->timezones->count();      # 4
$country->timezones->default();    # Timezone("America/Noronha")
$country->timezones->toStrings();  # ["America/Noronha", "America/Belem", "America/Sao_Paulo", ...]
```

#### Getting all timezones

Returns all `Timezone` objects for the country:

```php
use TinyBlocks\Country\Country;
use TinyBlocks\Country\Alpha2Code;

$country = Country::from(alphaCode: Alpha2Code::BRAZIL);

$country->timezones->all(); # [Timezone("America/Noronha"), Timezone("America/Belem"), ...]
```

#### Getting the default timezone

Returns the primary timezone (first in the IANA list). Falls back to `UTC` for territories without an assigned timezone:

```php
use TinyBlocks\Country\Country;
use TinyBlocks\Country\Alpha2Code;

$country = Country::from(alphaCode: Alpha2Code::JAPAN);
$country->timezones->default(); # Timezone("Asia/Tokyo")

$country = Country::from(alphaCode: Alpha2Code::BOUVET_ISLAND);
$country->timezones->default(); # Timezone("UTC")
```

#### Finding a timezone by identifier with UTC fallback

Searches for a specific IANA identifier within the country's timezones. Returns UTC if not found.

```php
use TinyBlocks\Country\Country;
use TinyBlocks\Country\Alpha2Code;

$country = Country::from(alphaCode: Alpha2Code::UNITED_STATES_OF_AMERICA);

$country->timezones->findByIdentifierOrUtc(iana: 'America/New_York'); # Timezone("America/New_York")
$country->timezones->findByIdentifierOrUtc(iana: 'Asia/Tokyo');       # Timezone("UTC")
```

#### Checking if a timezone belongs to the country

```php
use TinyBlocks\Country\Country;
use TinyBlocks\Country\Alpha2Code;

$country = Country::from(alphaCode: Alpha2Code::JAPAN);

$country->timezones->contains(iana: 'Asia/Tokyo');       # true
$country->timezones->contains(iana: 'America/New_York'); # false
```

<div id='license'></div>

## License

Country is licensed under [MIT](LICENSE).

<div id='contributing'></div>

## Contributing

Please follow the [contributing guidelines](https://github.com/tiny-blocks/tiny-blocks/blob/main/CONTRIBUTING.md) to
contribute to the project.
