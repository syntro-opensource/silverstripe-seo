<?php
namespace Syntro\SEOMeta\Extension;

use SilverStripe\Forms\DropdownField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\Assets\Image;
use Silverstripe\SiteConfig\SiteConfig;

use Syntro\SEOMeta\Generator\OGMetaGenerator;


class SEOPageExtension extends DataExtension {


    /**
     * @var int
     */
    private $optimal_title_length = 50;

    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'OGMetaType' => 'Varchar(20)',
        'OGMetaTitle' => 'Varchar',
        'OGMetaDescription' => 'Varchar',
        'OGMetaImage' => Image::class
    ];

    /**
     * Add default values to database
     * @var array
     */
    private static $defaults = [
        'OGMetaType' => 'website'
    ];


    /**
     * Update Fields
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {
        $owner = $this->owner;

        // calculate optimal title length
        $config = SiteConfig::current_site_config();
        $titleLength = $this->optimal_title_length - strlen($config->Title) - strlen($config->Tagline);

        // Move meta field to the new SEO-Tab
        $metaDescriptionField = $fields->dataFieldByName('MetaDescription');
        $metaExtraField = $fields->dataFieldByName('ExtraMeta');
        $fields->dataFieldByName('Title')->setTargetLength($titleLength);
        $fields->removeByName([
            'MetaDescription',
            'ExtraMeta',
            'Metadata'
        ]);
        $fields->addFieldToTab(
            'Root.SEO',
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
        $types = [];
        foreach (OGMetaGenerator::config()->available_types as $value) {
            $types[$value] = _t(OGMetaGenerator::class . '.'.$value, $value);
        }
        $fields->addFieldToTab(
            'Root.SEO',
            ToggleCompositeField::create(
                'OpenGraph',
                _t(__CLASS__.'.OpenGraphToggle', 'OpenGraph SEO (Facebook)'),
                [
                    $ogType = DropdownField::create('OGMetaType','Type',
                        $types
                    ),
                    $ogTitle = TextField::create('OGMetaTitle','Title'),
                    $ogImage = UploadField::create('OGMetaImage','Image'),
                    $ogDescription = TextareaField::create('OGMetaDescription','Description'),
                ]
            )->setHeadingLevel(4)
        );
        $ogTitle->setAttribute('placeholder', $owner->Title)->setTargetLength(50);
        $ogDescription->setAttribute('placeholder', $owner->MetaDescription);


        // Add Twitter Meta
        $fields->addFieldToTab(
            'Root.SEO',
            ToggleCompositeField::create(
                'Twitter',
                _t(__CLASS__.'.OpenGraphToggle', 'Twitter SEO'),
                [
                    $twitterTitle = TextField::create('TwitterTitle','Title'),
                    $twitterImage = UploadField::create('TwitterImage','Image'),
                    $twitterDescription = TextareaField::create('TwitterDescription','Description'),
                ]
            )->setHeadingLevel(4)
        );
        $twitterTitle->setAttribute('placeholder', $owner->Title)->setTargetLength(50);
        $twitterDescription->setAttribute('placeholder', $owner->MetaDescription);



        return $fields;
    }

    public function MetaComponents(&$tags)
    {
        $tags['test'] = [
            'tag' => 'meta',
            'attributes' => [
                'test' => 'true'
            ],
            'content' => null
        ];
    }
}
