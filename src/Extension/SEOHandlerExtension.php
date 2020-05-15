<?php
namespace Syntro\Seo\Extension;

use SilverStripe\ORM\DataObject;
use Page;

use SilverStripe\Forms\DropdownField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Assets\Image;
use Silverstripe\SiteConfig\SiteConfig;
use SilverStripe\Control\Controller;
use SilverStripe\VersionedAdmin\Controllers\HistoryViewerController;

use Syntro\Seo\Seo;
use Syntro\Seo\Forms\SeoAnalysisField;
use Syntro\Seo\Preview\SERPPreview;
use Syntro\Seo\Generator\OGMetaGenerator;
use Syntro\Seo\Generator\TwitterMetaGenerator;


/**
 * The SEOHandler extension applies the necessary functionality
 * to the Page object to handle automatic metadata generation
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class SEOHandlerExtension extends DataExtension
{

    /**
     * @var Seo
     */
    protected $seo_handler;

    /**
     * Get the Seo Handler of this object
     *
     * @return Seo
     */
    private function getHandler()
    {
        if ($this->seo_handler) {
            return $this->seo_handler;
        }
        $this->seo_handler = Seo::create($this->owner);
        return $this->seo_handler;
    }


    /**
     * setMetaSource - sets the source object from which meta information
     * is taken
     *
     * @param  DataObject|Page $value description
     * @return void
     */
    public function setMetaSource($value)
    {
        $handler = $this->getHandler();
        $handler->setObject($value);
    }


    /**
     * getSeoManager - returns the instance of the Seo manager associated with
     * this instance
     *
     * @return Seo  description
     */
    // public function getSeoManager()
    // {
    //     return $this->seo_handler;
    // }


    /**
     * MetaComponents - we extend the meta components in this hook.
     *
     * @param  array &$tags description
     * @return void
     */
    public function MetaComponents(&$tags)
    {
        $seoManager = $this->getHandler();

        $tags = array_merge($tags, $seoManager->getOGTags());
        $tags = array_merge($tags, $seoManager->getTwitterTags());
        $tags = array_merge($tags, $seoManager->getOtherTags());
    }
}
