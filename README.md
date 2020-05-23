# Silverstripe SEO

[![Build Status](https://travis-ci.org/syntro-opensource/silverstripe-seo.svg?branch=master)](https://travis-ci.org/syntro-opensource/silverstripe-seo)
[![codecov](https://codecov.io/gh/syntro-opensource/silverstripe-seo/branch/master/graph/badge.svg)](https://codecov.io/gh/syntro-opensource/silverstripe-seo)
[![phpstan](https://img.shields.io/badge/PHPStan-enabled-success)](https://github.com/phpstan/phpstan)
[![composer](https://img.shields.io/packagist/dt/syntro/silverstripe-seo-meta?color=success&logo=composer)](https://packagist.org/packages/syntro/silverstripe-seo-meta)

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

## Meta Tags
This module aims to provide a good set of default tags. By default, the
following tags are populated for a page:
```html
<!-- OpenGraph -->
<meta property="og:type" content="...">
<meta property="og:name" content="...">
<meta property="og:title" content="...">
<meta property="og:url" content="...">
<meta property="og:description" content="...">
<meta property="og:image" content="...">
<!-- twitter -->
<meta name="twitter:card" content="...">
<meta name="twitter:site" content="...">
<!-- modification dates -->
<meta property="article:published_time" content="...">
<meta property="article:modified_time" content="...">
```
> Not included in this list is the default description tag and the manually
> entered tags in the CMS, as these are populated by the cms.

These tags are populated with information available on the `Page` class. For some
tags, there are fallbacks which are used to populate the content. The `og:description`
tag will fall back to the default description and the `og:image` will fall back
to a global default which can be set in the site config.

### Editing Meta Tags
This module allows any developer to customize the tags which are pushed to the
page. Tags are handled by a metadata object assigned to the page object. There
are the following methods to change tags:
```php
use Syntro\Seo\Tags\Tag;

$metadata = $this->getMetadata();
$metadata
    ->pushTag(Tag::create(
        'TagName',
        [
            'property' => 'og:site_name',
            'content' => 'My Site'
        ],
        'meta'
    ))
    ->removeTag('TagName');
```
#### On the Object Level
You can change tags on the object level by defining a method called `UpdateMetadata`
on your class:
```php

/**
 * UpdateMetadata - returns an array containing Tags which should be added to
 * the page head.
 *
 * @return void
 */
public function UpdateMetadata()
{
    $metadata = $this->getMetadata();
    // Do what you need to do here
}
```
This method will be executed as the last step once the page is rendered.

#### On the Action Level
Sometimes, you might need to set specific metadata for certain Controller actions,
for example if you are using DataObjects as pages.

In these cases, you can simply use `$this->getMetadata()` inside of the action
and edit the Tag list to your liking.

#### Editing the Default Tags
The default tags are named as follows:

| Tag Name                   | Source Method                  |
|:-------------------------- |:------------------------------ |
| `'og:type'`                | `->OGTypeForTemplate()`        |
| `'og:name'`                | `->OGNameForTemplate()`        |
| `'og:title'`               | `->OGTitleForTemplate()`       |
| `'og:url'`                 | `->AbsoluteLink()`             |
| `'og:description'`         | `->OGDescriptionForTemplate()` |
| `'og:image'`               | `->OGImageForTemplate()`       |
| `'twitter:card'`           | `->TwitterCardForTemplate()`   |
| `'twitter:site'`           | `->TwitterSiteForTemplate()`   |
| `'article:published_time'` | `->Created`                    |
| `'article:modified_time'`  | `->LastEdited`                 |

You can use these names to update the list of tags. You can also overwrite the
getter methods stated above. This allows you to control the content of these
tags on the class level.
