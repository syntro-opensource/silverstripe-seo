<?php

namespace Syntro\SEO\Analysis;

use Syntro\SEO\Analysis\Analysis;

/**
 * checks the content for the focus keyword
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class ContentFocusAnalysis extends Analysis
{
    const CONTENT_FOCUS_NOT_FOUND = 'CONTENT_FOCUS_NOT_FOUND';
    const CONTENT_FOCUS_SUCCESS = 'CONTENT_FOCUS_SUCCESS';
    const CONTENT_FOCUS_UNSET = 'CONTENT_FOCUS_UNSET';

    /**
     * isHidden - if true, this analysis should be hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        if (!$this->getFocus()) {
            return true;
        }
        return false;
    }

    /**
     * getOptions - returns an array containing possible outcomes of this analysis
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            static::CONTENT_FOCUS_NOT_FOUND => [
                static::STATE_BAD,
                _t(__CLASS__ . '.NOT_FOUND', 'CONTENT_FOCUS_NOT_FOUND')
            ],
            static::CONTENT_FOCUS_SUCCESS => [
                static::STATE_GOOD,
                _t(__CLASS__ . '.SUCCESS', 'CONTENT_FOCUS_SUCCESS ({occurrences})', ['occurrences' => $this->findOccurrences()])
            ],
            static::CONTENT_FOCUS_UNSET => [
                static::STATE_NONE,
                _t(__CLASS__ . '.UNSET', 'CONTENT_FOCUS_UNSET')
            ],
        ];
    }

    /**
     * findOccurrences
     *
     * @return int
     */
    public function findOccurrences()
    {
        $content = $this->getPageContent();
        if (!strlen($content) || !$this->getFocus()) {
            return 0;
        }
        return substr_count(strtolower($content), strtolower($this->getFocus()));
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
            return static::CONTENT_FOCUS_UNSET;
        }

        if (!strstr(strtolower($this->getPageContent()), strtolower($this->getFocus()))) {
            return static::CONTENT_FOCUS_NOT_FOUND;
        }

        return static::CONTENT_FOCUS_SUCCESS;
    }
}
