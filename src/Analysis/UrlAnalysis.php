<?php

namespace Syntro\Seo\Analysis;

use Syntro\Seo\Analysis\Analysis;
use SilverStripe\View\Parsers\URLSegmentFilter;
use SilverStripe\CMS\Controllers\ContentController;

/**
 * checks the title
 */
 class UrlAnalysis extends Analysis
{

    const URL_KEYWORD_IRRELEVANT = 'URL_KEYWORD_IRRELEVANT';
    const URL_KEYWORD_NOT_IN_URL = 'URL_KEYWORD_NOT_IN_URL';
    const URL_KEYWORD_SUCCESS    = 'URL_KEYWORD_SUCCESS';
    const URL_KEYWORD_UNSET      = 'URL_KEYWORD_UNSET';

    /**
     * isHidden - if true, this analysis should be hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return $this->getState() == static::URL_KEYWORD_IRRELEVANT ||
            $this->getState() == static::URL_KEYWORD_UNSET;
    }

    /**
     * getOptions - returns an array containing possible outcomes of this analysis
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            static::URL_KEYWORD_IRRELEVANT => [
                static::STATE_NONE,
                _t(__CLASS__ . '.KEYWORD_IRRELEVANT', 'URL_KEYWORD_IRRELEVANT')
            ],
            static::URL_KEYWORD_NOT_IN_URL => [
                static::STATE_BAD,
                _t(__CLASS__ . '.KEYWORD_NOT_IN_URL', 'URL_KEYWORD_NOT_IN_URL')
            ],
            static::URL_KEYWORD_SUCCESS => [
                static::STATE_GOOD,
                _t(__CLASS__ . '.KEYWORD_SUCCESS', 'URL_KEYWORD_SUCCESS')
            ],
            static::URL_KEYWORD_UNSET => [
                static::STATE_NONE,
                _t(__CLASS__ . '.KEYWORD_UNSET', 'URL_KEYWORD_UNSET')
            ],
        ];
    }

    /**
     * getResult - returns the result of this analysis. The result must correspond
     * to a key in the getOptions() array.
     *
     * @return int|string
     */
    public function getResult()
    {
        if (!$this->getFocus()) {
            return static::URL_KEYWORD_UNSET;
        }

        $slug = URLSegmentFilter::create()->filter($this->getFocus());
        $path = parse_url($this->link)['path'];

        $page = ContentController::singleton()->Page('/');
        if (
            $page &&
            $page->URLSegment == 'home' &&
            !$page->ParentID &&
            $page->Link()==$path
        ) {
            return static::URL_KEYWORD_IRRELEVANT;
        }

        if (!strstr($path, $slug)) {
            return static::URL_KEYWORD_NOT_IN_URL;
        }

        return static::URL_KEYWORD_SUCCESS;
    }


}
