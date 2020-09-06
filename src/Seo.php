<?php
namespace Syntro\Seo;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\DataObject;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Blog\Model\BlogPost;
use Syntro\Seo\Generator\OGMetaGenerator;
use Syntro\Seo\Generator\TwitterMetaGenerator;
use Syntro\Seo\Generator\OtherMetaGenerator;
use SilverStripe\CMS\Model\SiteTree;
use Page;

/**
 * handles everything SEO related
 * @author Matthias Leutenegger <hello@syntro.ch>
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
    const GOOGLE_OPT_DESCRIPTION_LENGTH = 100;

    /**
     * @var int
     */
    const GOOGLE_MAX_DESCRIPTION_LENGTH = 160;

    /**
     * @var int
     */
    const GOOGLE_MIN_DESCRIPTION_LENGTH = 70;

    /**
     * @var int
     */
    const GOOGLE_MIN_CONTENT_LENGTH = 300;

    /**
     * Object we are working with
     * @var SiteTree
     */
    private $object;

    /**
     * __construct
     *
     * @param  Page $object the page we are working with
     * @return void
     */
    function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * setObject - sets the object from which
     *
     * @param  Page $value description
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
