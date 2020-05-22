<?php

namespace Syntro\Seo\Tags;

use SilverStripe\Core\Injector\Injectable;
use Syntro\Seo\Tags\Tag;

/**
 * An OG Tag specifically handles the generation of an OpenGraph tag.
 * An OG tag is a Tag in the following format:
 * "<meta property="..." content="..." />
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class OGTag extends Tag
{

    /**
     * __construct
     *
     * @param  string      $name     name of this tag, must be unique
     * @param  array       $data     the data in this tag
     * @param  null|string $tag=null the tag to use. by default, a 'meta' tag is created
     * @return void
     */

    /**
     * __construct
     *
     * @param  string $name     the tag name
     * @param  string $property the property value
     * @param  string $content  the content value
     * @return void
     */
    function __construct($name, $property, $content)
    {
        $data = [
            'property' => $property,
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
