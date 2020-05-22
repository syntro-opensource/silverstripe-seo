<?php

namespace Syntro\Seo\Tags;

use SilverStripe\Core\Injector\Injectable;
use Syntro\Seo\Tags\Tag;

/**
 * A twitter tag handles tag generation specifically for twitter related tags.
 * A twitter tag is a Tag in the following format:
 * "<meta name="twitter:..." content="..." />
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class TwitterTag extends Tag
{

    /**
     * __construct
     *
     * @param  string $name       name of this tag, must be unique
     * @param  array $data the data in this tag
     * @param  null|string $tag=null   the tag to use. by default, a 'meta' tag is created
     * @return void
     */
    function __construct($name, $property, $content)
    {
        $data = [
            'name' => $property,
            'content' => $content
        ];
        parent::__construct($name, $data, 'meta');
    }


    /**
     * forRender - helper to generate the array structure for generating
     * this tag.
     *
     * @return array
     */
    public function forRender()
    {
        $data = $this->getData();
        if (is_null($data['content']) or $data['content'] == '') {
            return [];
        }
        return parent::forRender();
    }
}
