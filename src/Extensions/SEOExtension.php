<?php
namespace Syntro\SEO\Extensions;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataExtension;

/**
 * The SEO extension adds a n SEO analysis Tab
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class SEOExtension extends DataExtension
{
    const ICON_STATE_WARN = "⚠️";

    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'MetaTitle' => 'Varchar',
    ];

    /**
     * Add default values to database
     *  @var array
     */
    private static $defaults = [
    ];

    /**
     * fields to be translated by fluent
     * @var array
     */
    private static $field_include = [
        'SEOPrimaryFocus',
        'SEOSecondaryFocus',
        'MetaTitle',
        'MetaDescription',
        'ExtraMeta'
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
        $fields->removeByName([
            'Metadata',
            'MetaDescription',
            'ExtraMeta'
        ]);
        $fields->findOrMakeTab(
            "Root.SEO",
            "{$this->getSEOIcon()} {$owner->fieldLabel('Root.SEO')}"
        );

        $fields->addFieldsToTab(
            'Root.SEO',
            [
                $metatitle = TextField::create(
                    'MetaTitle',
                    'Meta Title'
                ),
                $metaFieldDesc = TextareaField::create(
                    'MetaDescription',
                    'Meta Description'
                ),
                ToggleCompositeField::create(
                        'Metadata',
                        _t(__CLASS__.'.MetadataToggle', 'Metadata'),
                        [
                            $metaFieldExtra = new TextareaField("ExtraMeta", $owner->fieldLabel('ExtraMeta'))
                        ]
                    )->setHeadingLevel(4)
            ]
        );
        $metatitle
            ->setTargetLength(50, 40, 60)
            ->setAttribute('placeholder', $owner->Title);
        $metaFieldDesc
            ->setTargetLength(120, 100, 158)
            ->setRows(4)
            ->setRightTitle(
                _t(
                    'SilverStripe\\CMS\\Model\\SiteTree.METADESCHELP',
                    "Search engines use this content for displaying search results (although it will not influence their ranking)."
                )
            )
            ->addExtraClass('help');
        $metaFieldExtra
            ->setRightTitle(
                _t(
                    'SilverStripe\\CMS\\Model\\SiteTree.METAEXTRAHELP',
                    "HTML tags for additional meta information. For example <meta name=\"customName\" content=\"your custom content here\">"
                )
            )
            ->addExtraClass('help');
        return $fields;
    }

    /**
     * updateFieldLabels - adds Fieldlabels
     *
     * @param  array $labels the original labels
     * @return array
     */
    public function updateFieldLabels(&$labels)
    {
        $labels['Root.SEO'] =  _t(__CLASS__ . '.SEO', 'SEO');
        return $labels;
    }

    public function getSEOIcon()
    {
        $owner = $this->getOwner();
        if (!$owner->MetaDescription || $owner->MetaDescription == '') {
            return self::ICON_STATE_WARN;
        }
        return '';
    }
}
