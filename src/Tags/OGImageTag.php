<?php

namespace Syntro\Seo\Tags;

use SilverStripe\Assets\Image;
use Syntro\Seo\Tags\Tag;
use Syntro\Seo\Tags\OGTag;

/**
 * An OG image Tag specifically handles the generation of an OpenGraph image tag.
 * It will return multiple tags indicating image size, type and alt text.
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class OGImageTag extends Tag
{

    /**
     * @var Image
     */
    protected $image;

    /**
     * __construct
     *
     * @param  string $name  the name of the Tag
     * @param  Image  $image the Image
     * @return void
     */
    function __construct($name, Image $image)
    {
        $this->image = $image;
        parent::__construct($name, []);
    }

    /**
     * getImage - returns the Image
     *
     * @return Image|null
     */
    public function getImage()
    {
        return $this->image;
    }


    /**
     * forRender - helper to generate the array structure for generating
     * this tag.
     *
     * @return array
     */
    public function forRender()
    {
        $name_prefix = $this->getName();
        $image = $this->getImage();
        if (!$image) {
            return [];
        }
        $taglist = [
            OGTag::create("$name_prefix:og:image", 'og:image', $image->getAbsoluteURL())->forRender(),
            OGTag::create("$name_prefix:og:image:type", 'og:image:type', $image->getMimeType())->forRender(),
            OGTag::create("$name_prefix:og:image:width", 'og:image:width', $image->getWidth())->forRender(),
            OGTag::create("$name_prefix:og:image:height", 'og:image:height', $image->getHeight())->forRender(),
            OGTag::create("$name_prefix:og:image:alt", 'og:image:alt', $image->getTitle())->forRender(),
        ];
        return $taglist;
    }
}
