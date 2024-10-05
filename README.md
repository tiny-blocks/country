# Country

[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

* [Overview](#overview)
* [Installation](#installation)
* [How to use](#how-to-use)
* [License](#license)
* [Contributing](#contributing)

<div id='overview'></div> 

## Overview

Value Object representing a country using [ISO-3166 specifications](https://www.iso.org/iso-3166-country-codes.html).

<div id='installation'></div>

## Installation

```bash
composer require tiny-blocks/country
```

<div id='how-to-use'></div>

## How to use

The library exposes country codes according
to [ISO-3166 specifications](https://www.iso.org/iso-3166-country-codes.html). Also, it is possible to create a
representation of a country that groups the codes and its name.

### Alpha2Code

A two-letter code that represents a country name, recommended as the general purpose code.

```php
$alpha2Code = Alpha2Code::UNITED_STATES_OF_AMERICA;

$alpha2Code->name;              # UNITED_STATES_OF_AMERICA
$alpha2Code->value;             # US
$alpha2Code->toAlpha3()->value; # USA
```

### Alpha3Code

A three-letter code that represents a country name, which is usually more closely related to the
country name.

```php
$alpha3Code = Alpha3Code::UNITED_STATES_OF_AMERICA;

$alpha3Code->name;              # UNITED_STATES_OF_AMERICA
$alpha3Code->value;             # USA
$alpha3Code->toAlpha2()->value; # US
```

### Country

A `Country` instance can be created using either an `Alpha-2` or `Alpha-3` code, along with an optional country name.
There are two main methods to create a `Country` object: `from` (which accepts objects) and `fromString` (which accepts
strings).

#### Creating from objects

You can create a `Country` instance using the `from` method by providing an `Alpha2Code` or `Alpha3Code` object.
Optionally, you can pass the name of the country. If no name is provided, the default is the English version of the
country name.

```php
$country = Country::from(alphaCode: Alpha2Code::UNITED_STATES_OF_AMERICA);

$country->name;          # United States of America
$country->alpha2->value; # US
$country->alpha3->value; # USA
```

or

```php
$country = Country::from(alphaCode: Alpha3Code::UNITED_STATES_OF_AMERICA);

$country->name;          # United States of America
$country->alpha2->value; # US
$country->alpha3->value; # USA
```

If you want to specify a custom name:

```php
$country = Country::from(alphaCode: Alpha3Code::UNITED_STATES_OF_AMERICA, name: 'United States');

$country->name;          # United States
$country->alpha2->value; # US
$country->alpha3->value; # USA
```

#### Creating from string

Alternatively, you can create a `Country` instance using the `fromString` method, which accepts an `Alpha-2` or
`Alpha-3` code as a string. This method is useful when the alpha code is provided as a string.

```php
$country = Country::fromString(alphaCode: 'US');

$country->name;          # United States of America
$country->alpha2->value; # US
$country->alpha3->value; # USA
```

You can also pass a custom country name when using the `fromString` method:

```php
$country = Country::fromString(alphaCode: 'USA', name: 'United States');

$country->name;          # United States
$country->alpha2->value; # US
$country->alpha3->value; # USA
```

<div id='license'></div>

## License

Country is licensed under [MIT](LICENSE).

<div id='contributing'></div>

## Contributing

Please follow the [contributing guidelines](https://github.com/tiny-blocks/tiny-blocks/blob/main/CONTRIBUTING.md) to
contribute to the project.
