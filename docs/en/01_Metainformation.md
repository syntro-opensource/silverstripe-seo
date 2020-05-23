# Meta Information
<!-- TOC depthFrom:1 depthTo:6 withLinks:1 updateOnSave:1 orderedList:0 -->

- [Meta Information](#meta-information)
	- [Editing Meta Tags](#editing-meta-tags)
		- [On the Object Level](#on-the-object-level)
		- [On the Action Level](#on-the-action-level)
		- [Editing the Default Tags](#editing-the-default-tags)

<!-- /TOC -->

This module aims to provide a good set of default meta tags. our philosophy
for managing meta tags ist, that this part should be as flexible as possible
and mainly aimed at the developer. The user may set additional tags via the
integrated extra tags field.

By default, the following tags are populated for a page:
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

## Editing Meta Tags
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
### On the Object Level
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

### On the Action Level
Sometimes, you might need to set specific metadata for certain Controller actions,
for example if you are using DataObjects as pages.

In these cases, you can simply use `$this->getMetadata()` inside of the action
and edit the Tag list to your liking.

### Editing the Default Tags
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
