<?php

namespace Syntro\Seo\Forms;

use SilverStripe\Forms\DatalessField;
use SilverStripe\Forms\FormField;
use SilverStripe\Control\Director;

/**
 * A field which provides an analysis of a specific page
 *
 * @author Matthias Leutenegger
 */
class SEOAnalysisField extends FormField
{

    const GOOGLE_MAX_TITLE_LENGTH = 70;
    const GOOGLE_MAX_DESCRIPTION_LENGTH = 160;

    /**
     * @config
     */
    protected $schemaDataType = FormField::SCHEMA_DATA_TYPE_CUSTOM;

    /**
     * @config
     */
    protected $schemaComponent = 'SEOAnalysisField';

    private $analysisLink = null;

    private $analysisKeyword = null;

    private ?int $pageId = null;

    private ?string $currentTitle = null;

    /**
     * __construct
     *
     * @param  string $name    the name of the field
     * @param  string $title   the title of the field
     * @param  string $link    the link to the page to analyse
     * @param  string $keyword = null optional keyword to use for analysis
     * @return void
     */
    function __construct($name, $title, $link, $keyword = null)
    {
        parent::__construct($name, $title);
        $this->analysisLink = $link;
        $this->analysisKeyword = $keyword;
        $this->addExtraClass('seo-analysis-field');
    }

    public function setPageId(int $pageId): static
    {
        $this->pageId = $pageId;
        return $this;
    }

    public function setCurrentTitle(string $title): static
    {
        $this->currentTitle = $title;
        return $this;
    }

    /**
     * getSchemaStateDefaults
     *
     * @return array
     */
    public function getSchemaStateDefaults()
    {
        $state = parent::getSchemaStateDefaults();
        $state['title'] = $this->Title();
        $state['link'] = $this->analysisLink;
        $state['keyword'] = $this->analysisKeyword;
        $state['rootUrl'] = Director::absoluteBaseURL();
        $state['pageId'] = $this->pageId ?? 0;
        $state['currentTitle'] = $this->currentTitle ?? '';

        return $state;
    }
}
