# Silverstripe SEO

[![Build Status](https://travis-ci.org/syntro-opensource/silverstripe-seo.svg?branch=master)](https://travis-ci.org/syntro-opensource/silverstripe-seo)
[![codecov](https://codecov.io/gh/syntro-opensource/silverstripe-seo/branch/master/graph/badge.svg)](https://codecov.io/gh/syntro-opensource/silverstripe-seo)
[![phpstan](https://img.shields.io/badge/PHPStan-enabled-success)](https://github.com/phpstan/phpstan)
[![composer](https://img.shields.io/packagist/dt/syntro/silverstripe-seo?color=success&logo=composer)](https://packagist.org/packages/syntro/silverstripe-seo)

Silverstripe module for simple SEO optimization of pages

> ### Disclaimer
> This Module is heavily inspired by the [`quinninteractive/silverstripe-seo`](https://github.com/Quinn-Interactive/silverstripe-seo)
> and was originally started as a learning project but grew beyond
> this intention.
>
> We want to provide:
> * Compatibility with `dnadesign/silverstripe-elemental`
> * SEO reports to find and fix problems quickly
> * i18n
>
> We aim to keep this module exchangeable with the [`quinninteractive/silverstripe-seo`](https://github.com/Quinn-Interactive/silverstripe-seo)
> module in terms of database column names.

## Introduction
This module does several things to your Silverstripe application:
* Provide as many meta tags as possible without configuration (drop-in SEO)
* Allow editors to edit the content of meta tags
* Allow editors to check the SEO quality on a per-page basis
* Allow developers to customize the meta tags generated on a per-action basis
* generate a report over all pages listing possible improvements

The core functionality of the module is split into two sections, Metadata
management and SEO reporting. Metadata management is focused around the
OpenGraph standard and is as flexible as possible, allowing any developer
to set and edit any tag as precisely as necessary. SEO reporting is independent
from the metadata management and uses the rendered HTML output of the
current page.

## Documentation
* [How to handle metainformation](docs/en/01_Metainformation.md)
