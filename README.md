# util-http-php

[![Build Status](https://travis-ci.org/traderinteractive/util-http-php.svg?branch=master)](https://travis-ci.org/traderinteractive/util-http-php)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/traderinteractive/util-http-php.svg?style=flat)](https://scrutinizer-ci.com/g/traderinteractive/util-http-php/)
[![Coverage Status](https://coveralls.io/repos/traderinteractive/util-http-php/badge.svg?branch=master&service=github)](https://coveralls.io/github/traderinteractive/util-http-php?branch=master)

[![Latest Stable Version](http://img.shields.io/packagist/v/traderinteractive/util-http.svg?style=flat)](https://packagist.org/packages/traderinteractive/util-http)
[![Total Downloads](http://img.shields.io/packagist/dt/traderinteractive/util-http.svg?style=flat)](https://packagist.org/packages/traderinteractive/util-http)
[![License](http://img.shields.io/packagist/l/traderinteractive/util-http.svg?style=flat)](https://packagist.org/packages/traderinteractive/util-http)

A collection of general util-httpities for making common programming tasks easier.

## Requirements

util-http-php requires PHP 7.3 (or later).

## Composer

To add the library as a local, per-project dependency use [Composer](http://getcomposer.org)! Simply add a dependency on
`traderinteractive/util-http` to your project's `composer.json` file such as:

```sh
composer require traderinteractive/util-http
```

## Documentation

Found in the [source](src) itself, take a look!

## Contact

Developers may be contacted at:

 * [Pull Requests](https://github.com/traderinteractive/util-http-php/pulls)
 * [Issues](https://github.com/traderinteractive/util-http-php/issues)

## Project Build

With a checkout of the code get [Composer](http://getcomposer.org) in your PATH and run:

```sh
./vendor/bin/phpcs
./vendor/bin/phpunit
```

There is also a [docker](http://www.docker.com/)-based
[fig](http://www.fig.sh/) configuration that will execute the build inside a
docker container.  This is an easy way to build the application:

```sh
fig run build
```

For more information on our build process, read through out our [Contribution Guidelines](CONTRIBUTING.md).
