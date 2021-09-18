<?php

namespace Syntro\Seo\Analysis;

use Syntro\Seo\Analysis\Analysis;

/**
 * checks the title
 */
 class DescriptionAnalysis extends Analysis
{
    const GOOGLE_OPT_DESCRIPTION_LENGTH = 100;
    const GOOGLE_MAX_DESCRIPTION_LENGTH = 160;
    const GOOGLE_MIN_DESCRIPTION_LENGTH = 70;

    const DESCRIPTION_NO_FOCUS_KEYWORD = 'DESCRIPTION_NO_FOCUS_KEYWORD';
    const DESCRIPTION_SUCCESS          = 'DESCRIPTION_SUCCESS';
    const DESCRIPTION_TOO_LONG         = 'DESCRIPTION_TOO_LONG';
    const DESCRIPTION_TOO_SHORT        = 'DESCRIPTION_TOO_SHORT';
    const DESCRIPTION_UNSET            = 'DESCRIPTION_UNSET';

    /**
     * isHidden - if true, this analysis should be hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
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
            static::DESCRIPTION_NO_FOCUS_KEYWORD => [
                static::STATE_WARN,
                _t(__CLASS__ . '.NO_FOCUS_KEYWORD', 'DESCRIPTION_NO_FOCUS_KEYWORD')
            ],
            static::DESCRIPTION_SUCCESS => [
                static::STATE_GOOD,
                _t(__CLASS__ . '.SUCCESS', 'DESCRIPTION_SUCCESS')
            ],
            static::DESCRIPTION_TOO_LONG => [
                static::STATE_BAD,
                _t(__CLASS__ . '.TOO_LONG', 'DESCRIPTION_TOO_LONG')
            ],
            static::DESCRIPTION_TOO_SHORT => [
                static::STATE_WARN,
                _t(__CLASS__ . '.TOO_SHORT', 'DESCRIPTION_TOO_SHORT')
            ],
            static::DESCRIPTION_UNSET => [
                static::STATE_BAD,
                _t(__CLASS__ . '.UNSET', 'DESCRIPTION_UNSET')
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
        $dom = $this->getDom();
        $keyword = $this->getFocus();
        $description = $dom->find('meta[name=description]', 0);
        $description = !is_null($description)
            ? $description->getAttributes()['content']
            : null;

        if (!$description) {
            return static::DESCRIPTION_UNSET;
        }

        if (strlen($description) < static::GOOGLE_MIN_DESCRIPTION_LENGTH) {
            return static::DESCRIPTION_TOO_SHORT;
        }

        if (strlen($description) > static::GOOGLE_MAX_DESCRIPTION_LENGTH) {
            return static::DESCRIPTION_TOO_LONG;
        }

        if ($keyword && !strstr(strtolower($description), strtolower($keyword))) {
            return static::DESCRIPTION_NO_FOCUS_KEYWORD;
        }

        return static::DESCRIPTION_SUCCESS;
    }


}
