# Silverstripe SEO
Silverstripe module for simple SEO optimization of pages

[![Build Status](https://travis-ci.org/syntro-opensource/silverstripe-seo.svg?branch=master)](https://travis-ci.org/syntro-opensource/silverstripe-seo)
[![codecov](https://codecov.io/gh/syntro-opensource/silverstripe-seo/branch/master/graph/badge.svg)](https://codecov.io/gh/syntro-opensource/silverstripe-seo)
[![phpstan](https://img.shields.io/badge/PHPStan-enabled-success)](https://github.com/phpstan/phpstan)
[![composer](https://img.shields.io/packagist/dt/syntro/silverstripe-seo?color=success&logo=composer)](https://packagist.org/packages/syntro/silverstripe-seo)

> **Disclaimer**: This module is still in alpha state and noy et registred on packagist


## Introduction
This module does several things to your Silverstripe application:

* Provide as many meta tags as possible without configuration (drop-in SEO)
* Provide editors with a comprehensive list of fields to edit metainformation
* Allow developers to customize the meta tags generated on the object- or action-level
* Allow editors to check the SEO quality on a per-page basis
* generate a report over all pages listing possible improvements

The core functionality of the module is split into two sections, Metadata
management and SEO reporting. Metadata management is focused around the
OpenGraph standard and is as flexible as possible, allowing any developer
to set and edit any tag as precisely as necessary. SEO reporting is independent
from the metadata management and uses the rendered HTML output of the
current page.

## Installation
> **Disclaimer**: This module is still in alpha state and noy et registred on packagist

To install this module, run the following command:
```
composer require syntro/silverstripe-seo
```
Thats it.

## Documentation
* [How to handle metainformation](docs/en/01_Metainformation.md)
