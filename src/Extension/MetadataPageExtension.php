<?php
namespace Syntro\Seo\Extension;

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
use Silverstripe\SiteConfig\SiteConfig;
use Syntro\Seo\Metadata;
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
     * @var Metadata
     */
    protected $metadata;

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
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {

        $owner = $this->owner;

        // Move meta field to the new Metadata-Tab
        $metaDescriptionField = $fields->dataFieldByName('MetaDescription');
        $metaExtraField = $fields->dataFieldByName('ExtraMeta');
        if (!is_null($metaDescriptionField) && !is_null($metaExtraField)) {
            $fields->removeByName([
                'MetaDescription',
                'ExtraMeta',
                'Metadata'
            ]);
            $fields->addFieldToTab(
                'Root.Metadata',
                ToggleCompositeField::create(
                    'Metadata',
                    _t(__CLASS__.'.MetadataToggle', 'Metadata'),
                    [
                        $metaDescriptionField,
                        $metaExtraField
                    ]
                )->setHeadingLevel(4)
            );
            $metaDescriptionField->setTargetLength(125);


            // Add Opengraph Meta
            $OGTypes = [];
            foreach (Metadata::config()->available_og_types as $value) {
                $OGTypes[$value] = _t(Metadata::class . '.'.$value, $value);
            }
            $fields->addFieldToTab(
                'Root.Metadata',
                ToggleCompositeField::create(
                    'OpenGraph',
                    _t(__CLASS__.'.OpenGraphToggle', 'OpenGraph Metadata (Facebook)'),
                    [
                        $ogType = DropdownField::create('OGType','Type',
                            $OGTypes
                        ),
                        $ogTitle = TextField::create('OGTitle','Title'),
                        $ogImage = UploadField::create('OGImage','Image'),
                        $ogDescription = TextareaField::create('OGDescription','Description'),
                    ]
                )->setHeadingLevel(4)
            );
            $ogTitle->setAttribute('placeholder', $owner->Title)->setTargetLength(50);
            $ogDescription->setAttribute('placeholder', $owner->MetaDescription);


            // Add Twitter Meta
            $TwitterTypes = [];
            foreach (Metadata::config()->available_twitter_types as $value) {
                $TwitterTypes[$value] = _t(Metadata::class . '.'.$value, $value);
            }
            $fields->addFieldToTab(
                'Root.Metadata',
                ToggleCompositeField::create(
                    'Twitter',
                    _t(__CLASS__.'.OpenGraphToggle', 'Twitter Metadata'),
                    [
                        $twitterType = DropdownField::create('TwitterType','Type',
                            $TwitterTypes
                        ),
                        $twitterTitle = TextField::create('TwitterCreator','Creator'),
                    ]
                )->setHeadingLevel(4)
            );
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
     * @param  array &$tags description
     * @return void
     */
    public function MetaComponents(&$tags)
    {

        $owner = $this->getOwner();
        if (ClassInfo::hasMethod($owner,'UpdateMetadata')) {
            $owner->UpdateMetadata();
        }
        $metadata = $this->getMetadata();
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
