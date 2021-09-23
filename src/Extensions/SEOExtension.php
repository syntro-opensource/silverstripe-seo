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
use Syntro\SEO\Forms\SERPField;
use Syntro\SEO\Forms\KeywordAnalysisField;
use PHPHtmlParser\Dom;

/**
 * The SEO extension adds a n SEO analysis Tab
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class SEOExtension extends DataExtension
{
    const STATE_ICON_MAP = [
        '1' => '🟢',
        '0' => '',
        '-1' => '🟡',
        '-2' => '🔴',
        '-3' => '⚠️',
        '-4' => '❌'
    ];

    protected $SEODom = null;

    protected $state_keywordanalysis = 0;

    private static $seo_use_metatitle = true;
    private static $seo_title_fallback = 'Title';
    private static $seo_title_template = null;

    private static $seo_title_min = 40;
    private static $seo_title_opt = 50;
    private static $seo_title_max = 60;

    private static $seo_desc_min = 100;
    private static $seo_desc_opt = 120;
    private static $seo_desc_max = 158;

    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'MetaTitle' => 'Varchar',
        'FocusKeyword' => 'Varchar',
        'MetaDescription' => 'Text',
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
        'ExtraMeta',
        'FocusKeyword'
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
            'ExtraMeta',
            'FocusKeyword'
        ]);
        $fields->findOrMakeTab(
            "Root.SEO",
            "{$this->getSEOIcon()} {$owner->fieldLabel('Root.SEO')}"
        );

        $fields->addFieldToTab(
            'Root.SEO',
            TabSet::create('SEORoot')
        );

        $fields->findOrMakeTab(
            "Root.SEO.SEORoot.KWAnalysis",
            $owner->fieldLabel('Root.SEO.SEORoot.KWAnalysis')
        );
        $fields->findOrMakeTab(
            "Root.SEO.SEORoot.Meta",
            "{$this->getSEOMetaIcon()} {$owner->fieldLabel('Root.SEO.SEORoot.Meta')}"
        );

        /**
         * We add the SEO relevant metadata to a tab
         */
        $fields->addFieldsToTab(
            'Root.SEO.SEORoot.Meta',
            [
                $metaFieldDesc = TextareaField::create(
                    'MetaDescription',
                    _t(__CLASS__ . '.MetaDescription', 'Meta Description')
                ),
                // TextareaField::create(
                //     'PageContent',
                //     'PageContent',
                // )->setReadOnly(true)->setValue(file_get_contents($owner->AbsoluteLink()))
            ]
        );

        $metaFieldDesc
            ->setTargetLength(
                $owner->config()->seo_desc_opt,
                $owner->config()->seo_desc_min,
                $owner->config()->seo_desc_max
            )
            ->setRows(4)
            ->setRightTitle(
                _t(
                    'SilverStripe\\CMS\\Model\\SiteTree.METADESCHELP',
                    "Search engines use this content for displaying search results (although it will not influence their ranking)."
                )
            )
            ->addExtraClass('help');
        if ($owner->obj('ExtraMeta')) {
            $fields->addFieldToTab(
                'Root.SEO.SEORoot.Meta',
                ToggleCompositeField::create(
                    'Metadata',
                    _t(__CLASS__ . '.ExtraMetadataToggle', 'Extra Metadata'),
                    [
                        $metaFieldExtra = new TextareaField(
                            "ExtraMeta",
                            $owner->fieldLabel('ExtraMeta')
                        )
                    ]
                )->setHeadingLevel(4),
            );
            $metaFieldExtra
                ->setRightTitle(
                    _t(
                        'SilverStripe\\CMS\\Model\\SiteTree.METAEXTRAHELP',
                        "HTML tags for additional meta information. For example <meta name=\"customName\" content=\"your custom content here\">"
                    )
                )
                ->addExtraClass('help');
        }


        if ($owner->config()->seo_use_metatitle) {
            $fields->addFieldToTab(
                'Root.SEO.SEORoot.Meta',
                $metatitle = TextField::create(
                    'MetaTitle',
                    _t(__CLASS__ . '.MetaTitle', "Meta title")
                ),
                'MetaDescription'
            );
            $metatitle
                ->setTargetLength(
                    $owner->config()->seo_title_opt,
                    $owner->config()->seo_title_min,
                    $owner->config()->seo_title_max
                )
                ->setAttribute('placeholder', $owner->Title)
                ->setRightTitle(
                    _t(
                        __CLASS__ . '.MetaTitleRightTitle',
                        "This is the title used by search engines for displaying search results. Make sure to keep it similar to the <h1> tag."
                    )
                );
        } else {
            $fields->fieldByName('Root.Main.Title')->setTargetLength(
                $owner->config()->seo_title_opt,
                $owner->config()->seo_title_min,
                $owner->config()->seo_title_max
            );
        }

        /**
         * Add the keyword analysis fields
         */
        if ($owner->hasMethod('Link')) {
            $fields->addFieldsToTab(
                'Root.SEO.SEORoot.KWAnalysis',
                [
                    $focusKWField = TextField::create(
                        'FocusKeyword',
                        _t(__CLASS__ . '.FocusKeyword', 'Focus Keyword')
                    ),
                    $SERPField = SERPField::create(
                        'SERP',
                        _t(__CLASS__ . '.SERP', 'SERP'),
                        $owner->Link(),
                        $owner->FocusKeyword
                    ),
                    $KWAnalysisField = KeywordAnalysisField::create(
                        'KWAnalysis',
                        _t(__CLASS__ . '.KWAnalysis', 'Analysis results'),
                        $owner->Link(),
                        $owner->FocusKeyword
                    ),
                    // ToggleCompositeField::create('Passed', 'Passed', []),
                    // ToggleCompositeField::create('NotApplicable', 'Not Applicable', []),
                ]
            );
            $focusKWField
                ->setRightTitle(_t(__CLASS__ . '.FocusKeywordRightTitle', 'Choose a Focus for this Page'));
            $SERPField
                ->setRightTitle(_t(__CLASS__ . '.SERPRightTitle', 'Google preview'));
        } else {
            $noLinkMessage = _t(__CLASS__ . '.NOLINK', 'NO_LINK');
            $fields->addFieldToTab(
                'Root.SEO.SEORoot.KWAnalysis',
                LiteralField::create('NoLink', <<<HTML
                    <div class="alert alert-danger">
                        $noLinkMessage
                    </div>
                 HTML)
            );
        }
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
        $labels['Root.SEO.SEORoot.Meta'] =  _t(__CLASS__ . '.MetaTab', 'Meta');
        $labels['Root.SEO.SEORoot.KWAnalysis'] =  _t(__CLASS__ . '.KWAnalysisTab', 'Keyword Analysis');
        return $labels;
    }

    /* --------------------------------------------------- */
    /*                  icon Getter                        */
    /* --------------------------------------------------- */

    /**
     * getSEOIcon - returns the icon displayed in the Root tab
     *
     * @return string
     */
    public function getSEOIcon()
    {
        $state = $this->getMetaState();
        return $this->getIconFromState($state);
    }

    /**
     * getSEOMetaIcon - returns the icon from the Metadata tab
     *
     * @return string
     */
    public function getSEOMetaIcon()
    {
        $state = $this->getMetaState();
        return $this->getIconFromState($state);
    }

    /**
     * getMetaState - returns the state integer which symbolizes the quality
     * of the metadata
     *
     * @return int
     */
    public function getMetaState()
    {
        $owner = $this->getOwner();
        $state = 0;
        if (!$owner->MetaDescription || $owner->MetaDescription == '') {
            $state =  -3;
        } elseif (strlen(utf8_decode($owner->MetaDescription)) < $owner->config()->seo_desc_min ||
            strlen(utf8_decode($owner->MetaDescription)) > $owner->config()->seo_desc_max
        ) {
            $state =  -1;
        }

        return $state;
    }

    /**
     * getIconFromState - returns a string representation of the state
     *
     * @param  int $state the state value
     * @return string
     */
    public function getIconFromState($state)
    {
        if (isset(self::STATE_ICON_MAP[strval($state)])) {
            return self::STATE_ICON_MAP[strval($state)];
        } elseif ($state < 0) {
            return '🚫';
        }
        return '';
    }

    /* --------------------------------------------------- */
    /*                  Getter methods for meta            */
    /* --------------------------------------------------- */

    /**
     * getSEOTitle - returns a title for this object, which is derived from
     * MetaTitle > Title
     *
     * @return string
     */
    public function getSEOTitle()
    {
        $owner = $this->getOwner();
        $titleField = $owner->config()->seo_title_fallback;
        $useMetaTitle = $owner->config()->seo_use_metatitle;
        $titleObj = null;
        if ($titleField) {
            if ($useMetaTitle && $owner->MetaTitle && $owner->MetaTitle != '') {
                $titleObj = $owner->obj('MetaTitle');
            } else {
                $titleObj = $owner->obj($titleField);
            }
        }
        $titleTemplate = $owner->config()->seo_title_template;
        if ($titleObj) {
            if ($titleTemplate) {
                return $titleObj->renderWith($titleTemplate);
            }
            return $titleObj->forTemplate();
        }
        return null;
    }
}
