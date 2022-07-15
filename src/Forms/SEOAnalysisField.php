<?php

namespace Syntro\SEO\Forms;

use SilverStripe\Forms\DatalessField;
use SilverStripe\Forms\FormField;
use SilverStripe\Control\Director;

class SEOAnalysisField extends FormField
{

    const GOOGLE_MAX_TITLE_LENGTH = 70;
    const GOOGLE_MAX_DESCRIPTION_LENGTH = 160;

    protected $schemaDataType = FormField::SCHEMA_DATA_TYPE_CUSTOM;

    protected $schemaComponent = 'SEOAnalysisField';

    private $analysisLink = null;

    private $analysisKeyword = null;

    /**
     * __construct
     *
     * @param  string $name  the name of the field
     * @param  string $title the title of the field
     * @return void
     */
    function __construct($name, $title, $link, $keyword=null)
    {
        parent::__construct($name, $title);
        $this->analysisLink = $link;
        $this->analysisKeyword = $keyword;
        $this->addExtraClass('seo-analysis-field');
    }

    public function getSchemaStateDefaults()
    {
        $state = parent::getSchemaStateDefaults();
        $state['title'] = $this->Title();
        $state['link'] = $this->analysisLink;
        $state['keyword'] = $this->analysisKeyword;
        $state['rootUrl'] = Director::host();
        return $state;
    }
}
