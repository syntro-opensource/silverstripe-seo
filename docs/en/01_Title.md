# Page Title
The title of a Page is central to a good SEO Strategy. Title content and length
are vital properties in order to be placed well in a google search.

With silverstripe cms, you typically let the framework render the title tag
automatically (which corresponds to the page name) or overwrite the default
title in the `Page.ss` template. While both strategies work, we have found that,
especially with page objects, the title tag is often different from the page name.

We therefore add an additional field to the page object, the `meta title`, which
lets an editor overwrite the title specifically. This is the default setup when
installing this module, but you can change this behaviour by using the following
configuration options:

## Disable the metatitle field

In order to disable the meta title option entirely, set the following option:

```yaml
SilverStripe\CMS\Model\SiteTree:
  seo_use_metatitle: false

# Or with DataObjects:
Article:
  extensions:
    - Syntro\SEO\Extensions\SEOExtension
  seo_use_metatitle: false
```

The object/page will then fall back to the default way of rendering the page name
as meta title

## Use a template to render the title

This feature is especially useful to render titles of
[DataObjects used as page](./02_DOAP), but it also allows you to render a
specific title for specific pagetypes. In order to define a template per class,
use the following option:

```yaml
SilverStripe\CMS\Model\SiteTree:
  seo_title_template: Includes/Title

# Or with DataObjects:
Article:
  extensions:
    - Syntro\SEO\Extensions\SEOExtension
  seo_title_template: Includes/BlogTitle
```

> **IMPORTANT**: Keep in mind that you have to use the built-in `$MetaTags`
> to render the title
