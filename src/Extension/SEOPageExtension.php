<?php
namespace Syntro\Seo\Extension;

use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TabSet;
use SilverStripe\Assets\Image;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Control\Controller;
use SilverStripe\VersionedAdmin\Controllers\HistoryViewerController;
use SilverStripe\View\SSViewer;
use SilverStripe\CMS\Model\RedirectorPage;
use SilverStripe\CMS\Model\VirtualPage;
use SilverStripe\ErrorPage\ErrorPage;
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
        'MetaTitle' => 'Varchar',
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
        $owner = $this->owner;
        // stop when we are dealing with a redirector or virtual page
        if (
            $owner instanceof RedirectorPage ||
            $owner instanceof VirtualPage ||
            $owner instanceof ErrorPage
        ) {
            return $fields;
        }

        // avoid breaking the history comparison UI
        // when adding the Analysis field
        if (!(Controller::curr() instanceof HistoryViewerController)) {
            $fields->findOrMakeTab(
                "Root.SEOAnalysis",
                $owner->fieldLabel('Root.SEOAnalysis')
            );
            $fields->addFieldsToTab(
                'Root.SEOAnalysis',
                [
                    $healthFocusKeywordField = TextField::create('FocusKeyword', _t(__CLASS__ . '.FocusKeywordTitle', 'Focus Keyword')),
                    $SeoAnalysisField = SeoAnalysisField::create('SEOAnalysis', $owner),
                    $SERPPreviewField = LiteralField::create('SERPPreview', SERPPreview::create($owner)),
                ]
            );
            $healthFocusKeywordField
                ->setRightTitle(_t(__CLASS__ . '.KeyWordRight', 'Set a Keyword which you want to focus this page around'));
        }

        /**
         * @var TextareaField|null
         */
        $metaDescriptionField = $fields->dataFieldByName('MetaDescription');
        $metaDescriptionField->setTargetLength(
            Seo::GOOGLE_OPT_DESCRIPTION_LENGTH,
            Seo::GOOGLE_MIN_DESCRIPTION_LENGTH,
            Seo::GOOGLE_MAX_DESCRIPTION_LENGTH
        );
        /**
         * @var TextareaField|null
         */
        $metaExtraField = $fields->dataFieldByName('ExtraMeta');
        $metaExtraField->setRows(10);

        $fields->findOrMakeTab(
            "Root.SEOMeta",
            $owner->fieldLabel('Root.SEOMeta')
        );

        $fields->addFieldsToTab(
            'Root.SEOMeta',
            [
                $metaTitleField = TextField::create('MetaTitle', _t(__CLASS__ . '.MetaTitleTitle', 'Page Title')),
                $metaDescriptionField,
                $metaExtraField
            ]
        );
        // $emptytitlelength = strlen(
        //     SSViewer::create('Includes/Title')->process(null)->__toString()
        // );
        $metaTitleField
            ->setAttribute('placeholder', $owner->Title)
            ->setRightTitle(_t(__CLASS__ . '.MetaTitleRight', 'The title of this page as displayed by search engines. Try to keep it similar to the page name.'))
            ->setTargetLength(
                Seo::GOOGLE_OPT_TITLE_LENGTH,
                Seo::GOOGLE_MIN_TITLE_LENGTH,
                Seo::GOOGLE_MAX_TITLE_LENGTH
            );



        $fields->removeByName([
            'Metadata'
        ]);

        return $fields;
    }

    /**
     * MetaComponents - we extend the meta components in this hook.
     *
     * @param  array $tags the original tags
     * @return void
     */
    public function MetaComponents(&$tags)
    {

        $owner = $this->getOwner();
        // overwrite default page title
        // TODO: add test
        // if ($owner->config()->use_templated_meta_title) {
        $titleTag=$tags['title'];
        $metatitle =
            !is_null($owner->MetaTitle) || $owner->MetaTitle != ''
            ? $owner->obj('MetaTitle')
            : $owner->obj('Title');
        $titleTag['content'] = $metatitle->forTemplate();//= $metatitle->renderWith(SSViewer::create('Includes/Title'));
        $tags['title'] = $titleTag;
        // }
    }

    /**
     * updateFieldLabels - adds Fieldlabels
     *
     * @param  array $labels the original labels
     * @return array
     */
    public function updateFieldLabels(&$labels)
    {
        $labels['Root.SEOAnalysis'] = _t(__CLASS__ . '.SEO_Analysis', 'SEO Analysis');
        $labels['Root.SEOMeta'] = _t(__CLASS__ . '.SEO_Meta', 'SEO Meta');
        return $labels;
    }
}
