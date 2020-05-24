<?php
namespace Syntro\Seo\Extension;

use SilverStripe\View\SSViewer;
use SilverStripe\Forms\HeaderField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Core\ClassInfo;
use SilverStripe\SiteConfig\SiteConfig;
use Syntro\Seo\Metadata;
use Syntro\Seo\Seo;
use Page;

/**
 * The MetadataPageExtension applies the necessary functionality
 * to the Page object to handle automatic metadata generation
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class MetadataPageExtension extends DataExtension
{

    /**
     * @config
     * @var bool
     */
    private static $use_templated_meta_title = true;


    /**
     * @var Metadata|null
     */
    protected $metadata = null;

    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'MetaTitle' => 'Varchar',
        'OGType' => 'Varchar(20)',
        'OGTitle' => 'Varchar',
        'OGDescription' => 'Varchar',
        'TwitterType' => 'Varchar(20)'
    ];

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = [
        'OGImage' => Image::class
    ];

    /**
     * Relationship version ownership
     * @var array
     */
    private static $owns = [
        'OGImage'
    ];

    /**
     * Add default values to database
     * @var array
     */
    private static $defaults = [
        'OGType' => 'website',
        'TwitterType' => 'summary'
    ];

    /**
     * Update Fields
     *
     * @param  FieldList $fields the original fields
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {

        $owner = $this->owner;

        // Move meta field to the new Metadata-Tab

        /**
         * @var TextareaField|null
         */
        $metaDescriptionField = $fields->dataFieldByName('MetaDescription');
        /**
         * @var TextareaField|null
         */
        $metaExtraField = $fields->dataFieldByName('ExtraMeta');
        // we push the Meta fields to a new tab
        if (!is_null($metaDescriptionField) && !is_null($metaExtraField)) {
            $fields->removeByName([
                'MetaDescription',
                'ExtraMeta'
            ]);
            // generate available types
            $OGTypes = [];
            foreach (Metadata::config()->available_og_types as $value) {
                $OGTypes[$value] = _t(Metadata::class . '.' . $value, $value);
            }
            $TwitterTypes = [];
            foreach (Metadata::config()->available_twitter_types as $value) {
                $TwitterTypes[$value] = _t(Metadata::class . '.' . $value, $value);
            }

            $fields->addFieldsToTab(
                'Root.Metadata',
                [
                    $metaDescriptionField,
                    $SMHeaderField = HeaderField::create('SocialMediaHeader', _t(__CLASS__ . '.SOCIALMEDIASHARING', 'Social Media Sharing')),
                    $ogImage = UploadField::create('OGImage', _t(__CLASS__ . '.OGImageTitle', 'OpenGraph Image')),
                    $ogTitle = TextField::create('OGTitle', _t(__CLASS__ . '.OGTitleTitle', 'OpenGraph Title')),
                    $ogDescription = TextareaField::create('OGDescription', _t(__CLASS__ . '.OGDescriptionTitle', 'OpenGraph Description')),
                    $ogType = DropdownField::create('OGType', _t(__CLASS__ . '.OGTypeTitle', 'OpenGraph Type'), $OGTypes),
                    $twitterType = DropdownField::create('TwitterType', _t(__CLASS__ . '.TwitterTypeTitle', 'Twitter Type'), $TwitterTypes),
                    $metaToggle = ToggleCompositeField::create(
                        'ExtraTags',
                        _t(__CLASS__ . '.ExtraMetaTagsToggle', 'Extra Meta Tags'),
                        [
                            $metaExtraField
                        ]
                    )
                ]
            );
            // $SMHeaderField
            //     ->addExtraClass('mt-5 mb-4')
            //     ->setHeadingLevel(2);
            $metaDescriptionField
                ->setTargetLength(
                    Seo::GOOGLE_MIN_DESCRIPTION_LENGTH +
                    (Seo::GOOGLE_MAX_DESCRIPTION_LENGTH-Seo::GOOGLE_MIN_DESCRIPTION_LENGTH)/1.5
                );
            $SMHeaderField
                ->addExtraClass('mt-5 mb-4')
                ->setHeadingLevel(2);
            $ogTitle
                ->setRightTitle(_t(__CLASS__ . '.OGTitleRight', 'The title which is shown when you share this page.'))
                ->setAttribute('placeholder', $owner->Title);
            $ogDescription
                ->setAttribute('placeholder', $owner->MetaDescription)
                ->setRightTitle(_t(__CLASS__ . '.OGDescriptionRight', 'The summary which is shown when you share this page.'));
            $ogType
                ->setRightTitle(_t(__CLASS__ . '.OGTypeRight', 'The type which is used to display this page when shared. Most of the time, you want this to be "website"'));
            $twitterType
                ->setRightTitle(_t(__CLASS__ . '.TwitterTypeRight', 'The type which is used to display this page when shared on Twitter. Most of the time, you want this to be "summary"'));
            $metaToggle
                ->setHeadingLevel(4);
            $metaExtraField
                ->setRows(20);

            // add some dialog to indicate image
            $OgImageRightTitle = _t(__CLASS__ . '.OGImageRight', 'The image which is shown when you share this page.');
            if (!$owner->OGImageID && !SiteConfig::current_site_config()->OGDefaultImageID) {
                $ogImage->setRightTitle(
                    $OgImageRightTitle.' '._t(__CLASS__ . '.NODEAFAULTIMAGE', 'No default Image is set. This means, a crawler might select one at random.')
                );
            } elseif (!$owner->OGImageID && SiteConfig::current_site_config()->OGDefaultImageID) {
                $ogImage->setRightTitle(
                    $OgImageRightTitle.' '._t(__CLASS__ . '.DEFAULTIMAGE', 'The default image set in the siteconfig will be used.')
                );
            }

            // add a metatitle field if configured:
            if ($owner->config()->use_templated_meta_title) {
                $fields->addFieldToTab(
                    'Root.Metadata',
                    $metaTitleField = TextField::create('MetaTitle',_t(__CLASS__ . '.MetaTitleTitle', 'Page Title')),
                    'MetaDescription'
                );
                $emptytitlelength = strlen(
                    SSViewer::create('Includes/Title')->process(null)->__toString()
                );
                $metaTitleField
                    ->setAttribute('placeholder', $owner->Title)
                    ->setTargetLength(Seo::GOOGLE_MAX_TITLE_LENGTH-$emptytitlelength)
                    ->setRightTitle(_t(__CLASS__ . '.MetaTitleRight', 'The title of this page as displayed by search engines. Try to keep it similar to the page name.'));
            }

        }
        return $fields;
    }


    /**
     * Get the Metadata of this object
     *
     * @return Metadata
     */
    public function getMetadata()
    {
        if ($this->metadata) {
            return $this->metadata;
        }
        $this->metadata = Metadata::create($this->getOwner());
        return $this->metadata;
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
        if (ClassInfo::hasMethod($owner, 'UpdateMetadata')) {
            $owner->__call('UpdateMetadata',[]);
        }
        $metadata = $this->getMetadata();

        // overwrite default page title
        // TODO: add test
        if ($owner->config()->use_templated_meta_title) {
            $titleTag=$tags['title'];
            $metatitle =
                !is_null($owner->MetaTitle) || $owner->MetaTitle != ''
                ? $owner->obj('MetaTitle')
                : $owner->obj('Title');
            $titleTag['content'] = $metatitle->renderWith(SSViewer::create('Includes/Title'));
            $tags['title'] = $titleTag;
        }


        $tags = array_merge($tags, $metadata->getTagsForRender());
    }


    /**
     * OGTypeForTemplate - fetches the type of this object
     *
     * @return string|null
     */
    public function OGTypeForTemplate()
    {
        $owner = $this->getOwner();
        if ($owner->OGType) {
            return $owner->OGType;
        }
        return null;
    }

    /**
     * OGNameForTemplate - return the name for the og:name property
     *
     * @return string|null
     */
    public function OGNameForTemplate()
    {
        return SiteConfig::current_site_config()->Title;
    }

    /**
     * OGTitleForTemplate - returns the Title of this Page
     *
     * @return string
     */
    public function OGTitleForTemplate()
    {
        $owner = $this->getOwner();
        if ($owner->OGTitle) {
            return $owner->OGTitle;
        }
        if ($owner->MetaTitle) {
            return $owner->OGTitle;
        }
        return $owner->Title;
    }

    /**
     * OGDescriptionForTemplate - returns the description.
     * falls back to the meta description
     *
     * @return string
     */
    public function OGDescriptionForTemplate()
    {
        $owner = $this->getOwner();
        if ($owner->OGDescription) {
            return $owner->OGDescription;
        }
        return $owner->MetaDescription;
    }

    /**
     * OGImageForTemplate - returns an Image. Falls back to a global default
     * set in the siteconfig
     *
     * @return Image|null
     */
    public function OGImageForTemplate()
    {
        $owner = $this->getOwner();
        if ($owner->OGImageID > 0) {
            return $owner->OGImage;
        } elseif (SiteConfig::current_site_config()->OGDefaultImage) {
            return SiteConfig::current_site_config()->OGDefaultImage;
        } else {
            return null;
        }
    }

    /**
     * TwitterCardForTemplate - returns the card type to be used for the twitter
     * preview
     *
     * @return string
     */
    public function TwitterCardForTemplate()
    {
        return $this->getOwner()->TwitterType;
    }

    /**
     * TwitterSiteForTemplate - returns the twitter id of the site,
     * set in the SiteConfig
     *
     * @return string
     */
    public function TwitterSiteForTemplate()
    {
        return SiteConfig::current_site_config()->TwitterSite;
    }
}
