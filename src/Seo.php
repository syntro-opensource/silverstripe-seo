<?php
namespace Syntro\Seo;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use Silverstripe\SiteConfig\SiteConfig;
use SilverStripe\Blog\Model\BlogPost;
use Syntro\Seo\Generator\OGMetaGenerator;
use Syntro\Seo\Generator\TwitterMetaGenerator;
use Syntro\Seo\Generator\OtherMetaGenerator;


/**
 * handles everything SEO related
 */
class Seo
{
    use Injectable, Configurable;

    /**
     * @var int
     */
    const GOOGLE_MAX_TITLE_LENGTH = 70;

    /**
     * @var int
     */
    const GOOGLE_OPT_TITLE_LENGTH = 40;


    /**
     * @var int
     */
    const GOOGLE_MIN_TITLE_LENGTH = 30;


    /**
     * @var int
     */
    const GOOGLE_MAX_DESCRIPTION_LENGTH = 160;

    /**
     * @var int
     */
    const GOOGLE_MIN_DESCRIPTION_LENGTH = 70;

    /**
     * Object we are working with
     * @var DataObject|SiteTree
     */
    private $object = null;


    /**
     * __construct - description
     *
     * @param  SiteTree $object the page we are working with
     * @return void
     */
    function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * getOGTags - returns a keyed array of meta tag attributes.
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
     * getTwitterTags - returns a keyed array of meta tag attributes.
     *
     * @see HTML::createTag()
     * @return array
     */
    public function getTwitterTags()
    {
        $TwitterGenerator = TwitterMetaGenerator::create();
        $TwitterGenerator->setTwitterType($this->getTwitterType());
        $TwitterGenerator->setTwitterSite($this->getTwitterSite());
        $TwitterGenerator->setTwitterCreator($this->getTwitterCreator());

        return $TwitterGenerator->process();
    }

    /**
     * getOtherTags - returns a keyed array of meta tag attributes.
     *
     * @see HTML::createTag()
     * @return array
     */
    public function getOtherTags()
    {
        $OtherGenerator = OtherMetaGenerator::create();
        $OtherGenerator->setPublishDate($this->getPublishDate());
        $OtherGenerator->setChangeDate($this->getChangeDate());
        $OtherGenerator->setCanonicalURL($this->object->AbsoluteLink());
        return $OtherGenerator->process();
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
        if ($this->object->OGTitle) {
            return $this->object->OGTitle;
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
        if ($this->object->OGDescription) {
            return $this->object->OGDescription;
        } elseif (
            class_exists(BlogPost::class) &&
            $this->object instanceof BlogPost
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
        } elseif ($this->object->OGType) {
            return $this->object->OGType;
        }
        return null;
    }

    /**
     * getOGImage - returns an image to be used for the og:image tag.
     * This takes the fallback options into account in the following order:
     * object::OGImage (> BlogPost::FeaturedImage) > SiteConfig::OGMetaDefaultImage
     * If no suitable image is provided, this returns null.
     *
     * @return Image|null
     */
    public function getOGImage()
    {
        if ($this->object->OGImageID > 0) {
            return $this->object->OGImage;
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



    /**
     * getTwitterType
     *
     * @return string|null
     */
    public function getTwitterType()
    {
        return $this->object->TwitterType;
    }

    /**
     * getTwitterSite
     *
     * @return string|null
     */
    public function getTwitterSite()
    {
        return SiteConfig::current_site_config()->TwitterSite;
    }


    /**
     * getTwitterCreator
     *
     * @return string|null
     */
    public function getTwitterCreator()
    {
        return $this->object->TwitterCreator;
    }

    /**
     * getPublishDate
     *
     * @return string
     */
    public function getPublishDate()
    {
        return $this->object->dbObject('Created')->Rfc3339();
    }

    /**
     * getChangeDate
     *
     * @return string
     */
    public function getChangeDate()
    {
        return $this->object->dbObject('LastEdited')->Rfc3339();
    }
}
