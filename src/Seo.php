<?php
namespace Syntro\SEOMeta;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use Silverstripe\SiteConfig\SiteConfig;
use SilverStripe\Blog\Model\BlogPost;


/**
 * handles everything SEO related
 */
class Seo
{
    use Injectable, Configurable;

    /**
     * Object we are working with
     * @var DataObject|SiteTree
     */
    private $object = null;


    function __construct($object)
    {
        $this->object = $object;
    }


    /**
     * getOGImage - returns an image to be used for the og:image tag.
     * This takes the fallback options into account in the following order:
     * object::OGMetaImage (> BlogPost::FeaturedImage) > SiteConfig::OGMetaDefaultImage
     * If no suitable image is provided, this returns null.
     * 
     * @return Image|null
     */
    public function getOGImage()
    {
        if ($this->object->OGMetaImageID > 0) {
            return $this->object->OGMetaImage;
        } elseif (
            class_exists(BlogPost::class) &&
            get_class($this->object) == BlogPost::class &&
            $this->object->FeaturedImageID > 0
        ) {
            return $this->object->FeaturedImage;
        } elseif (SiteConfig::current_site_config()->OGMetaDefaultImage) {
            return SiteConfig::current_site_config()->OGMetaDefaultImage;
        } else {
            return null;
        }
    }
}
