# Country

[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

* [Overview](#overview)
* [Installation](#installation)
* [How to use](#how-to-use)
* [License](#license)
* [Contributing](#contributing)

<div id='overview'></div> 

## Overview

Value Object representing a country using ISO-3166 specifications.

<div id='installation'></div>

## Installation

```bash
composer require tiny-blocks/country
```

<div id='how-to-use'></div>

## How to use

The library exposes country codes according to ISO-3166 specifications. Also, it is possible to create a
representation of a country that groups the codes and its name.

### Alpha2Code

**Alpha-2 code**: a two-letter code that represents a country name, recommended as the general purpose code.

```php
$alpha2Code = Alpha2Code::UNITED_STATES_OF_AMERICA;

$alpha2Code->name;              # UNITED_STATES_OF_AMERICA
$alpha2Code->value;             # US
$alpha2Code->toAlpha3()->value; # USA
```

### Alpha3Code

**Alpha-3 code**: a three-letter code that represents a country name, which is usually more closely related to the
country name.

```php
$alpha3Code = Alpha3Code::UNITED_STATES_OF_AMERICA;

$alpha3Code->name;              # UNITED_STATES_OF_AMERICA
$alpha3Code->value;             # USA
$alpha3Code->toAlpha2()->value; # US
```

### Country

A country might change a significant part of its name, so there is no specific ISO for this case.

You can create an instance of `Country`, using the `from` method, informing an alpha code (`Alpha2Code`or `Alpha3Code`),
where they can be of type `AlphaCode` or just `strings`. Optionally, you can enter the name of the country. If the
country name is not given in the `from` method, then the default is to assume an English version of the country name.

```php
$country = Country::from(alphaCode: Alpha2Code::UNITED_STATES_OF_AMERICA);

$country->name;          # United States of America
$country->alpha2->value; # US
$country->alpha3->value; # USA
```

or

```php
$country = Country::from(alphaCode: 'US');

$country->name;          # United States of America
$country->alpha2->value; # US
$country->alpha3->value; # USA
```

Creating an instance passing the country name.

```php
$country = Country::from(alphaCode: Alpha3Code::UNITED_STATES_OF_AMERICA, name: 'United States');

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
