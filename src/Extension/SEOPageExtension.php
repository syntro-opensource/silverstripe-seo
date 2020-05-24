<?php
namespace Syntro\Seo\Extension;

use SilverStripe\Forms\DropdownField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Assets\Image;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Control\Controller;
use SilverStripe\VersionedAdmin\Controllers\HistoryViewerController;

use Syntro\Seo\Seo;
use Syntro\Seo\Forms\SeoAnalysisField;
use Syntro\Seo\Preview\SERPPreview;
use Syntro\Seo\Generator\OGMetaGenerator;
use Syntro\Seo\Generator\TwitterMetaGenerator;

/**
 * Extension to handle the display of an SERP field
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class SEOPageExtension extends DataExtension
{

    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'FocusKeyword' => 'Varchar',
    ];


    /**
     * Update Fields
     *
     * @param FieldList $fields the original fields
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {
        if (Controller::curr() instanceof HistoryViewerController) { // avoid breaking the history comparison UI
            return $fields;
        }
        $owner = $this->owner;

        // Add the SEO Health fields
        $fields->addFieldToTab(
            'Root.SEO',
            $healthFocusKeywordField = TextField::create('FocusKeyword', 'Focus Keyword')
        );
        $fields->addFieldToTab(
            'Root.SEO',
            $SERPPreviewField = LiteralField::create('SERPPreview', SERPPreview::create($owner))
        );
        $fields->addFieldToTab(
            'Root.SEO',
            $SeoAnalysisField = SeoAnalysisField::create('SEOAnalysis', 'SEO Analysis', $owner)
        );
        $healthFocusKeywordField
            ->setRightTitle(_t(__CLASS__ . '.KeyWordDesc', 'Set a Keyword which you want to focus this page around'));



        return $fields;
    }
}
