# Silverstripe SEO
Silverstripe module for simple SEO optimization of pages

[![Build Status](https://travis-ci.org/syntro-opensource/silverstripe-seo.svg?branch=master)](https://travis-ci.org/syntro-opensource/silverstripe-seo)
[![codecov](https://codecov.io/gh/syntro-opensource/silverstripe-seo/branch/master/graph/badge.svg)](https://codecov.io/gh/syntro-opensource/silverstripe-seo)
![Dependabot](https://img.shields.io/badge/dependabot-active-brightgreen?logo=dependabot)
[![phpstan](https://img.shields.io/badge/PHPStan-enabled-success)](https://github.com/phpstan/phpstan)
[![composer](https://img.shields.io/packagist/dt/syntro/silverstripe-seo?color=success&logo=composer)](https://packagist.org/packages/syntro/silverstripe-seo)


## Introduction
This module does several things to your Silverstripe application:

* Provide as many meta tags as possible without configuration
* Provide editors with a comprehensive list of fields to control metadata for sharing links (e.g. on social media)
* Allow developers to customize the meta tags generated on the object- or action-level
* Allow editors to check the SEO quality on a per-page basis
<!-- * generate a report over all pages listing possible improvements -->

The core functionality of the module is split into two sections, Metadata
management and SEO reporting. Metadata management is focused around the
OpenGraph standard and is as flexible as possible, allowing any developer
to set and edit any tag as precisely as necessary. SEO reporting is independent
from the metadata management and allows to analyze the output of any page and
better communicate and control Title and meta info.

## Installation

To install this module, run the following command:
```
composer require syntro/silverstripe-seo
```
Thats it.

## Features
### Meta Tag Generation / Metainformation management
([see docs](docs/en/02_Metainformation.md)).

### SEO Analysis
![SEO](docs/img/SEO.png)

This module adds two new tabs to every page. in the analysis tab, the user is
informed about how well this page is optimized, and in the Metadata tab, he can
add a [metatitle](docs/en/01_Title.md) and description.

Target lengths on these fields help with limiting characters to a suitable limit.

### Social sharing
A new Tab is added to the cms, allowing any editor to tailor the way the page
is rendered by crawlers from social media platforms. Aditionally, an default
image to be used as fallback can be set in the siteconfig.

The fields provide Information about what they are used for, which allows any
editor to set those fields correctly.

## Documentation
* [How to edit the title](docs/en/01_Title.md)
* [How to handle metainformation](docs/en/02_Metainformation.md)
