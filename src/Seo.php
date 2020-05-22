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
    private $object;

    /**
     * __construct
     *
     * @param  DataObject|Page $object the page we are working with
     * @return void
     */
    function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * setObject - sets the object from which
     *
     * @param  DataObject|Page $value description
     * @return void
     */
    public function setObject($value)
    {
        $this->object = $value;
    }

    /**
     * setObject - sets the object from which
     *
     * @return  DataObject|Page $value
     */
    public function getObject()
    {
        return $this->object;
    }
}
