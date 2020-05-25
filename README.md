# Silverstripe SEO
Silverstripe module for simple SEO optimization of pages

[![Build Status](https://travis-ci.org/syntro-opensource/silverstripe-seo.svg?branch=master)](https://travis-ci.org/syntro-opensource/silverstripe-seo)
[![codecov](https://codecov.io/gh/syntro-opensource/silverstripe-seo/branch/master/graph/badge.svg)](https://codecov.io/gh/syntro-opensource/silverstripe-seo)
[![phpstan](https://img.shields.io/badge/PHPStan-enabled-success)](https://github.com/phpstan/phpstan)
[![composer](https://img.shields.io/packagist/dt/syntro/silverstripe-seo?color=success&logo=composer)](https://packagist.org/packages/syntro/silverstripe-seo)

> **Disclaimer**: This module is still in alpha state and not yet registred on packagist


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
from the metadata management and allows to analyze the output of any page.

## Installation
> **Disclaimer**: This module is still in alpha state and not yet registred on packagist

To install this module, run the following command:
```
composer require syntro/silverstripe-seo
```
Thats it.

## Features
### Meta Tag Generation / Metainformation management
A new Tab is added to the cms, allowing any editor to tailor the important
information on a per page basis. Aditionally, an default image to be used
as Fallback can be set in the siteconfig

The fields provide Information about what they are used for, which allows any
editor to set those fields correctly. Aditionnally, target Lengths have been
set for SEO relevant fields and fallbacks are shown in the fields if they apply.

For Developers, there is a method available on the `Page` object, which allows
to tailor the tag generation down to the action-level ([see docs](docs/en/02_Metainformation.md)).

### SEO Analysis
> **Disclaimer**: This Module was inspired by the
> [quinninteractive/silverstripe-seo](https://github.com/Quinn-Interactive/silverstripe-seo)
> module. The SEO analysis used currently is heavily inspired by the mentioned
> module.
>
> While it started out as a training project, we now intend to expand
> on this module. The key goal will be, to inject the SEO analysis in the Page
> Rendering, independent of the backend. This way, any url on the page
> can be checked, even if you use `DataObject`s as pages or if you have alternative
> actions on a `PageController`.

Description will follow.

## Documentation
* [How to edit the title](docs/en/01_Title.md)
* [How to handle metainformation](docs/en/02_Metainformation.md)
