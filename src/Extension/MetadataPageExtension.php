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
     * @var Metadata|null
     */
    protected $metadata = null;

    /**
     * Database fields
     * @var array
     */
    private static $db = [
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

        $OGTypes = [];
        foreach (Metadata::config()->available_og_types as $value) {
            $OGTypes[$value] = _t(Metadata::class . '.' . $value, $value);
        }
        $TwitterTypes = [];
        foreach (Metadata::config()->available_twitter_types as $value) {
            $TwitterTypes[$value] = _t(Metadata::class . '.' . $value, $value);
        }
        $fields->findOrMakeTab(
            "Root.SocialSharing",
            $owner->fieldLabel('Root.SocialSharing')
        );
        $fields->addFieldsToTab(
            'Root.SocialSharing',
            [
                $ogImage = UploadField::create('OGImage', _t(__CLASS__ . '.OGImageTitle', 'OpenGraph Image')),
                $ogTitle = TextField::create('OGTitle', _t(__CLASS__ . '.OGTitleTitle', 'OpenGraph Title')),
                $ogDescription = TextareaField::create('OGDescription', _t(__CLASS__ . '.OGDescriptionTitle', 'OpenGraph Description')),
                $toggleTypesField = ToggleCompositeField::create(
                    'Types',
                    _t(
                        __CLASS__ . '.RENDERTYPES',
                        'Render Types'
                    ),
                    [
                        $ogType = DropdownField::create('OGType', _t(__CLASS__ . '.OGTypeTitle', 'OpenGraph Type'), $OGTypes),
                        $twitterType = DropdownField::create('TwitterType', _t(__CLASS__ . '.TwitterTypeTitle', 'Twitter Type'), $TwitterTypes)
                    ]
                )
            ]
        );

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

        // add some dialog to indicate image
        $OgImageRightTitle = _t(__CLASS__ . '.OGImageRight', 'The image which is shown when you share this page.');
        if (!$owner->OGImageID && !SiteConfig::current_site_config()->OGDefaultImageID) {
            $ogImage->setRightTitle(
                $OgImageRightTitle . ' ' . _t(__CLASS__ . '.NODEAFAULTIMAGE', 'No default Image is set. This means, a crawler might select one at random.')
            );
        } elseif (!$owner->OGImageID && SiteConfig::current_site_config()->OGDefaultImageID) {
            $ogImage->setRightTitle(
                $OgImageRightTitle . ' ' . _t(__CLASS__ . '.DEFAULTIMAGE', 'The default image set in the siteconfig will be used.')
            );
        }
        return $fields;
    }

    /**
     * updateFieldLabels - adds Fieldlabels
     *
     * @param  array &$labels the original labels
     * @return array
     */
    public function updateFieldLabels(&$labels)
    {
        $labels['Root.SocialSharing'] = _t(__CLASS__ . '.Socialsharing', 'Social sharing');
        return $labels;
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
            $owner->__call('UpdateMetadata', []);
        }
        $metadata = $this->getMetadata();

        // // overwrite default page title
        // // TODO: add test
        // if ($owner->config()->use_templated_meta_title) {
        //     $titleTag=$tags['title'];
        //     $metatitle =
        //         !is_null($owner->MetaTitle) || $owner->MetaTitle != ''
        //         ? $owner->obj('MetaTitle')
        //         : $owner->obj('Title');
        //     $titleTag['content'] = $metatitle->renderWith(SSViewer::create('Includes/Title'));
        //     $tags['title'] = $titleTag;
        // }


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
