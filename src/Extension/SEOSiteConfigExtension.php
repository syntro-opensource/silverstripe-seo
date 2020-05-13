<?php
namespace Syntro\Seo\Extension;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\ToggleCompositeField;

/**
 *
 */
class SEOSiteConfigExtension extends DataExtension
{
    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'TwitterSite' => 'Varchar',
    ];

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = [
        'OGMetaDefaultImage' => Image::class,
    ];

    /**
     * Relationship version ownership
     * @var array
     */
    private static $owns = [
        'OGMetaDefaultImage'
    ];

    /**
     * Update Fields
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {
        $owner = $this->owner;
        $fields->addFieldToTab(
            'Root.Main',
            ToggleCompositeField::create(
                'Metadata',
                _t(__CLASS__.'.MetadataToggle', 'SEO Metadata'),
                [
                    $OGMetaDefaultImageField = UploadField::create('OGMetaDefaultImage','Default Image'),
                    $TwitterSiteField = TextField::create('TwitterSite','Twitter Site')
                ]
            )
        );
        return $fields;
    }
}
