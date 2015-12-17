# Ziptastic PHP

[![Build Status](https://travis-ci.org/Ziptastic/ziptastic-php.svg)](https://travis-ci.org/Ziptastic/ziptastic-php) [![Code Climate](https://codeclimate.com/github/Ziptastic/ziptastic-php/badges/gpa.svg)](https://codeclimate.com/github/Ziptastic/ziptastic-php) [![Test Coverage](https://codeclimate.com/github/Ziptastic/ziptastic-php/badges/coverage.svg)](https://codeclimate.com/github/Ziptastic/ziptastic-php/coverage)

This library is a simple interface for the [Ziptastic API](https://www.getziptastic.com/).

Using Ziptastic requires an API key.

## Installing

Ziptastic PHP can be installed via composer:

````
composer require ziptastic/ziptastic
````

## Usage

The simplest usage defaults to the provided Curl service:

````php
<?php

include "vendor/autoload.php";

$z = Ziptastic\Ziptastic\Lookup::create(getenv('ZIPTASTIC_API_KEY'));

$result = $z->lookup(48038);

?>
````

You can also use a normal instantiation technique:

````php
<?php

include "vendor/autoload.php";

$service = new Ziptastic\Ziptastic\Service\CurlService;
$z = new Ziptastic\Ziptastic\Lookup($service, getenv('ZIPTASTIC_API_KEY'));

$result = $z->lookup(48038);

?>
````

Results are returned as a collection of class LookupModel:

````php
<?php

$lookup = current($result);
echo $lookup->county(); // Macomb
echo $lookup->city(); // Clinton Township
echo $lookup->state(); // Michigan
echo $lookup->stateShort(); // MI
echo $lookup->postalCode(); // 48038
echo $lookup->latitude(); // 42.5868882
echo $lookup->longitude(); // -82.9195514

// timezone() returns an instance of \DateTimeZone
echo $lookup->timezone()->getName(); // America/Detroit

?>
````

## License

Ziptastic PHP is licensed under the MIT license.
