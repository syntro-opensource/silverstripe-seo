# Page Title
The title of a Page is central to a good SEO Strategy. Title content and length
are vital properties in order to be placed well in a google search.

Typically, SilverStripe either uses the Page title or allows the User to create
a Title as desired:
```html
<!-- as done by Silverstripe -->
$MetaTags
<!-- or free form -->
<title>$Title | $SiteConfig.Title</title>
$MetaTags(false)
```

Both versions are not ideal to give the editor an incentive to set a meaningful
title with the correct length when creating the page. Aditionally, the stock
Title field is often used in the page layout itself, where a lengthy SEO-tailored
title is not useful.

This Module by default uses a template to generate a title from a meta title
set alongside the page title (and falls back to the page title), which you can
overwrite in your page. The advantage of using a template is, that we can render
the template and set a target length on the title field to motivate the
creation of a meaningful title.

You can also just generate a custom title the same way you are used to. In this
case, we would recommend to disable the `MetaTitle` field and template functionality
via config to not confuse potential users:
```yaml
Syntro\Seo\Extension\MetadataPageExtension:
    use_templated_meta_title: false
```
