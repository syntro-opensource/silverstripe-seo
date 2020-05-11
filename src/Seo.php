<?php
namespace Syntro\SEOMeta;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use Silverstripe\SiteConfig\SiteConfig;
use SilverStripe\Blog\Model\BlogPost;
use Syntro\SEOMeta\Generator\OGMetaGenerator;


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
     * getOGTags - returns a keyed array of meta tag attributes.
     *
     * Array structure corresponds to arguments for HTML::create_tag(). Example:
     * $tags['og:description'] = [
     *     // html tag type, if omitted defaults to 'meta'
     *     'tag' => 'meta',
     *     // attributes of html tag
     *     'attributes' => [
     *         'name' => 'description',
     *         'content' => 'content',
     *     ],
     *     // content of html tag. (True meta tags don't contain content)
     *     'content' => null
     * ];
     *
     * @see HTML::createTag()
     * @return array
     */
    public function getOGTags()
    {
        $OGGenerator = OGMetaGenerator::create();
        $OGGenerator->setOGName($this->getOGName());
        $OGGenerator->setOGTitle($this->getOGTitle());
        $OGGenerator->setOGUrl($this->object->AbsoluteLink());
        $OGGenerator->setOGDescription($this->getOGDescription());
        $OGGenerator->setOGType($this->getOGType());
        $OGGenerator->setOGImage($this->getOGImage());

        return $OGGenerator->process();
    }


    /**
     * getOGName - returns the OG Name
     *
     * @return string
     */
    public function getOGName()
    {
        // if(SiteConfig::current_site_config()->OGSiteName) {
        //     return SiteConfig::current_site_config()->OGSiteName;
        // }
        return SiteConfig::current_site_config()->Title;
    }

    /**
     * getOGTitle - returns the OG Title for the record
     *
     * @return string
     */
    public function getOGTitle()
    {
        if ($this->object->OGMetaTitle) {
            return $this->object->OGMetaTitle;
        }
        return $this->object->Title;
    }

    /**
     * getOGDescription - returns the OG Description for the record
     *
     * @return string
     */
    public function getOGDescription()
    {
        if ($this->object->OGMetaDescription) {
            return $this->object->OGMetaDescription;
        } elseif (
            class_exists(BlogPost::class) &&
            get_class($this->object) == BlogPost::class
        ) {
            if ($this->object->Summary) {
                return $this->object->Summary;
            } elseif ($this->object->MetaDescription) {
                return $this->object->MetaDescription;
            }
            return $this->object->Excerpt();
        }
        return $this->object->MetaDescription;
    }

    /**
     * getOGType - returns the OG Type for the record
     *
     * @return string|null
     */
    public function getOGType()
    {
        if (
            class_exists(BlogPost::class) &&
            get_class($this->object) == BlogPost::class
        ) {
            return 'article';
        } elseif ($this->object->OGMetaType) {
            return $this->object->OGMetaType;
        }
        return null;
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
