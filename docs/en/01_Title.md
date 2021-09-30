# Page Title
The title of a Page is central to a good SEO Strategy. Title content and length
are vital properties in order to be placed well in a google search.

With silverstripe cms, you typically let the framework render the title tag
automatically (which corresponds to the page name) or overwrite the default
title in the `Page.ss` template.

We have found that, especially with page objects, the title tag is often
different from the page name. Therefore, this module adds an additional field to
the page object, the `meta title`, which lets an editor overwrite the title
specifically.

While you can let Silverstripe handle the rendering of the title tag using the
`$MetaTags` in the head section, this may become unwieldy when combined with
controllers that try to overwrite the Title, as this Title is then not picked
up by the module. We therefore recommend to render the title in your `Page.ss`
template as follows:

```html
    $MetaTags(false)
    <title>
      <% if $SEOSource %>
        $SEOSource.SEOTitle
      <% else %>
        $Title | $SiteConfig.Title
      <% end_if %>
    </title>
```
This will only render a specific title when a source has been set (in the DOAP
case) or when the current controller action is `index` (in the normal case of
rendering a page). Otherwise, the title will fall back to the normal behaviour.

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
[DataObjects used as page](./02_DOAP.md), but it also allows you to render a
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

## Change the title fallback field
This is very useful if working with [DataObjects used as page](./02_DOAP.md),
as it allows for configuring a decent fallback for any object without
relying on the editor to set a title manually. Keep in mind, that the title
for a DataObject will also fall back to the page name which is rendering the
object by default if this is not set!

```yaml
SilverStripe\CMS\Model\SiteTree:
  seo_title_fallback: SomeField

# Or with DataObjects:
Article:
  extensions:
    - Syntro\SEO\Extensions\SEOExtension
  seo_title_template: Includes/BlogTitle
  seo_title_fallback: Title
```
