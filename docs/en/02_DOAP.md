# Usage with DataObjects as pages

Depending on the content-heavyness of a DataObject-as-page setup, the content
and metadata presented to a crawler should actually be optimised as well.
This module allows you to do this by applying an extension to the DataObject
in question:

```yaml
Article:
  extensions:
    - Syntro\SEO\Extensions\SEOExtension
```

Now, you have to tell the page controller which is rendering the object, where
to get the metadata from:

```php
class BlogController extends PageController
{
    // ...
    //
    public function read(HTTPRequest $request)
    {
        $article = Article::get()->byID($request->param('ID'));
        $this->setSEOSource($article); // <- This is all you need
        return [
            'Article' => $article
        ]
    }
}
```

> If you want to control metadata for sharing on social media and such, check
> out our [syntro/silvershare](https://github.com/syntro-opensource/silvershare)
module!
