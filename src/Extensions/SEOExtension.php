<?php
namespace Syntro\SEO\Extensions;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
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

    const ICON_SCORE_GOOD = "🟢";
    const ICON_SCORE_RISKY = "🟠";
    const ICON_SCORE_BAD = "🔴";
    const ICON_SCORE_NA = "⚪️";

    const ICON_STATE_OK = "✔️";
    const ICON_STATE_WARN = "⚠️";

    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'SEOPrimaryFocus' => 'Varchar',
        'SEOSecondaryFocus' => 'Varchar',
        'MetaTitle' => 'Varchar',
        'EnableSEO' => 'Boolean',
    ];

    /**
     * Add default values to database
     *  @var array
     */
    private static $defaults = [
        'EnableSEO' => true
    ];

    /**
     * fields to be translated by fluent
     * @var array
     */
    private static $field_include = [
        'SEOPrimaryFocus',
        'SEOSecondaryFocus',
        'MetaTitle'
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
        $fields->findOrMakeTab(
            "Root.SEO",
            "{$this->getSEOIcon()} {$owner->fieldLabel('Root.SEO')}"
        );
        $fields->addFieldToTab('Root.SEO', new TabSet('Root'));
        $fields->findOrMakeTab(
            "Root.SEO.Root.MetaData",
            "✔️ {$owner->fieldLabel('Root.SEO.Root.MetaData')}"
        );
        $fields->findOrMakeTab(
            "Root.SEO.Root.OnPage",
            "🟢 {$owner->fieldLabel('Root.SEO.Root.OnPage')}"
        );
        $fields->findOrMakeTab(
            "Root.SEO.Root.Semantics",
            "🟢 {$owner->fieldLabel('Root.SEO.Root.Semantics')}"
        );
        $fields->addFieldToTab(
            'Root.SEO.Root.MetaData',
            LiteralField::create('infoMetaData', <<<HTML
                <p class="mb-3">
                    This Tab shows you all the data fields you can and should
                    use to make this page more appealing for search engines.
                </p>
            HTML)
        );

        $fields->addFieldsToTab(
            'Root.SEO',
            [
                $EnableSEO = CheckboxField::create(
                    'EnableSEO',
                    'Enable SEO'
                ),
                $primaryFocus = TextField::create(
                    'SEOPrimaryFocus',
                    'Focus keyword'
                ),
                $secondaryFocus = TextField::create(
                    'SEOSecondaryFocus',
                    'Secondary keywords'
                )
            ],
            'Root'
        );
        $primaryFocus
            ->setRightTitle(self::ICON_STATE_WARN);

        $fields->addFieldToTab(
            'Root.SEO.Root.OnPage',
            LiteralField::create('infoOnPage', <<<HTML
                <p class="mb-3">
                    This Tab shows on page tests and improvements: <br>
                    <ul>
                        <li>Focus keyword</li>
                        <ul>
                            <li>is unique</li>
                        </ul>
                        <li>Title Tag</li>
                        <ul>
                            <li>contains main keyword</li>
                            <li>main keyword is in front</li>
                            <li>correct length</li>
                        </ul>
                        <li>Meta description</li>
                        <ul>
                            <li>contains main keyword</li>
                            <li>correct length</li>
                            <li>contains secondary keywords</li>
                        </ul>
                        <li>Body</li>
                        <ul>
                            <li>length</li>
                            <li>contains focus keyword</li>
                            <li>contains secondary keywords</li>
                        </ul>
                        <li>URL</li>
                        <ul>
                            <li>contains focus keyword</li>
                        </ul>
                    </ul>
                </p>
            HTML)
        );

        $fields->addFieldToTab(
            'Root.SEO.Root.Semantics',
            LiteralField::create('infoSemantics', <<<HTML
                <p class="mb-3">
                    This tab analyzes the semantics of this page. This means,
                    how the page is structured and what information is present.
                    <br>
                    <ul>
                        <li>Title hierarchy</li>
                        <li>Links</li>
                        <li>Images</li>
                    </ul>
                </p>
            HTML)
        );

        $fields->addFieldsToTab(
            'Root.SEO.Root.MetaData',
            [
                $metatitle = TextField::create(
                    'MetaTitle',
                    'Meta Title'
                ),
                $metadesc = TextareaField::create(
                    'MetaDescription',
                    'Meta Description'
                ),

            ]
        );
        $metatitle
            ->setTargetLength(50, 40, 60)
            ->setAttribute('placeholder', $owner->Title);
        $metadesc
            ->setTargetLength(120, 100, 158)
            ->setRows(4)
            ->setRightTitle(self::ICON_STATE_WARN);
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
        $labels['Root.SEO.Root.MetaData'] =  _t(__CLASS__ . '.TAB_METADATA', 'Meta Data');
        $labels['Root.SEO.Root.OnPage'] =  _t(__CLASS__ . '.TAB_ONPAGE', 'On Page');
        $labels['Root.SEO.Root.Semantics'] =  _t(__CLASS__ . '.TAB_SEMANTICS', 'Semantics');
        return $labels;
    }

    public function getSEOIcon()
    {
        $owner = $this->getOwner();
        if (!$owner->EnableSEO) {
            return self::ICON_SCORE_NA;
        }
        return self::ICON_STATE_WARN;
    }
}
