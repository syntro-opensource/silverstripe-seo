<?php
namespace Syntro\SEO\Extensions;

use SilverStripe\Control\Director;
use SilverStripe\SiteConfig\SiteConfig;
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
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\CMS\Model\RedirectorPage;
use SilverStripe\CMS\Model\VirtualPage;
use SilverStripe\ErrorPage\ErrorPage;
use Syntro\SEO\Forms\SEOAnalysisField;
use SilverStripe\i18n\i18n;

/**
 * The SEO extension adds a n SEO analysis Tab
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class SEOExtension extends DataExtension
{

    /**
     * @config
     */
    private static $seo_use_metatitle = true;

    /**
     * @config
     */
    private static $seo_title_fallback = 'Title';

    /**
     * @config
     */
    private static $seo_title_template = null;


    /**
     * @config
     */
    private static $seo_title_min = 40;

    /**
     * @config
     */
    private static $seo_title_opt = 50;

    /**
     * @config
     */
    private static $seo_title_max = 60;


    /**
     * @config
     */
    private static $seo_desc_min = 100;

    /**
     * @config
     */
    private static $seo_desc_opt = 120;

    /**
     * @config
     */
    private static $seo_desc_max = 158;

    /**
     * Database fields
     * @config
     * @var array
     */
    private static $db = [
        'MetaTitle' => 'Varchar',
        'FocusKeyword' => 'Varchar',
        'MetaDescription' => 'Text',
    ];

    /**
     * Add default values to database
     * @config
     *  @var array
     */
    private static $defaults = [
    ];

    /**
     * fields to be translated by fluent
     * @config
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
        if ($owner instanceof RedirectorPage || $owner instanceof VirtualPage || $owner instanceof ErrorPage) {
            return $fields;
        }
        $fields->removeByName([
            'Metadata',
            'MetaDescription',
            'ExtraMeta',
            'FocusKeyword'
        ]);
        $fields->findOrMakeTab(
            "Root.SEO",
            $owner->fieldLabel('Root.SEO')
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
            $owner->fieldLabel('Root.SEO.SEORoot.Meta')
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
            ->setRows(4)
            ->setRightTitle(
                _t(
                    'SilverStripe\\CMS\\Model\\SiteTree.METADESCHELP',
                    "Search engines use this content for displaying search results (although it will not influence their ranking)."
                )
            )
            ->addExtraClass('help')
            ->setTargetLength(
                $owner->config()->seo_desc_opt,
                $owner->config()->seo_desc_min,
                $owner->config()->seo_desc_max
            );
        if ($owner->hasField('ExtraMeta')) {
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
                ->setAttribute('placeholder', $owner->Title)
                ->setRightTitle(
                    _t(
                        __CLASS__ . '.MetaTitleRightTitle',
                        "This is the title used by search engines for displaying search results. Make sure to keep it similar to the <h1> tag."
                    )
                )
                ->setTargetLength(
                    $owner->config()->seo_title_opt,
                    $owner->config()->seo_title_min,
                    $owner->config()->seo_title_max
                );
        } else {
            /** @var TextField $titlefield */
            $titlefield = $fields->fieldByName('Root.Main.Title');
            $titlefield->setTargetLength(
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
                    SEOAnalysisField::create(
                        'SEOAnalysis',
                        '',
                        $owner->Link(),
                        $owner->FocusKeyword
                    ),
                    // ToggleCompositeField::create('Passed', 'Passed', []),
                    // ToggleCompositeField::create('NotApplicable', 'Not Applicable', []),
                ]
            );
            $focusKWField
                ->setRightTitle(_t(__CLASS__ . '.FocusKeywordRightTitle', 'Choose a Focus for this Page'));
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
    /*                  Getter methods for meta            */
    /* --------------------------------------------------- */

    /**
     * getSEOTitle - returns a title for this object, which is derived from
     * MetaTitle > Title
     *
     * @return string|null
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

    /**
     * getSchemaGraph - returns the schema graph for this page or item
     *
     * @param  SiteTree $originalPage the page to create this graph from. Important if current object is not a page
     * @return string
     */
    public function getSchemaGraph(SiteTree $originalPage)
    {
        $owner = $this->getOwner();
        $siteconfig = SiteConfig::current_site_config();
        $generatedGraph = [
            '@context' => 'https://schema.org',
            '@graph' => [
                $siteconfig->getOrganisationSchema(),
                $siteconfig->getWebsiteSchema(),
                $this->getWebPageSchema(),
                $this->getBreadcrumbListSchema($originalPage),
            ]
        ];
        $owner->extend('updateSchemaGraph', $generatedGraph);
        return json_encode($generatedGraph);
    }


    /**
     * getWebPageSchema - generates the WebPage part of the schema
     *
     * @return array
     */
    public function getWebPageSchema()
    {
        $owner = $this->getOwner();
        $baseURL = Director::absoluteBaseURL();
        $currentURL = $owner->AbsoluteLink();
        return [
            "@type" => "WebPage",
            "@id" => "$currentURL#webpage",
            "url" => "$currentURL",
            "inLanguage" => i18n::get_locale(),
            "name" => $this->getSEOTitle(),
            "isPartOf" => [
                "@id" => "$baseURL#website"
            ],
            "datePublished" => $owner->Created,
            "dateModified" => $owner->LastEdited,
            "breadcrumb" => [
                "@id" => "$currentURL#breadcrumb"
            ],
            "potentialAction" => [
                [
                    "@type" => "ReadAction",
                    "target" => [
                        "$currentURL"
                    ]
                ]
            ],
        ];
    }

    /**
     * getBreadcrumbListSchema - generates the breadcrumb part of the schema
     *
     * @param  SiteTree $page the page to create the crumbs from. Important if current object is not a page
     * @return array
     */
    public function getBreadcrumbListSchema(SiteTree $page)
    {
        $owner = $this->getOwner();
        $baseURL = Director::absoluteBaseURL();
        $currentURL = $owner->AbsoluteLink();
        $breadCrumbs = [];
        $pagedummy = $page;
        if ($owner instanceof SiteTree) {
            $pagedummy = $owner;
        }
        if (!$pagedummy->isHomePage() && $homePage = SiteTree::get_by_link(null)) {
            $breadCrumbs[] = [
                "@type" => "ListItem",
                "name" => $homePage->getSEOTitle(),
                "item" => $baseURL
            ];
        }
        foreach ($page->getBreadcrumbItems() as $item) {
            $breadCrumbs[] = [
                "@type" => "ListItem",
                "name" => $item->getSEOTitle(),
                "item" => $item->AbsoluteLink()
            ];
        }
        // $breadCrumbs = array_reverse($breadCrumbs);
        if (!($owner instanceof SiteTree)) {
            $breadCrumbs[] = [
                "@type" => "ListItem",
                "name" => $owner->getSEOTitle(),
                "item" => $owner->AbsoluteLink()
            ];
        }
        for ($i=0; $i < count($breadCrumbs); $i++) {
            $breadCrumbs[$i]['position'] = $i+1;
        }
        return [
            "@type" => "BreadcrumbList",
            "@id" => "$currentURL#breadcrumb",
            "itemListElement" => $breadCrumbs,
        ];
    }
}
