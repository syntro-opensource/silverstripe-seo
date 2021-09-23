<?php

namespace Syntro\SEO\Analysis;

use Syntro\SEO\Analysis\Analysis;

/**
 * checks the h1 title tag
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class H1Analysis extends Analysis
{
    const H1_NOT_FOUND = 'H1_NOT_FOUND';
    const H1_SUCCESS = 'H1_SUCCESS';
    const H1_MULTIPLE = 'H1_MULTIPLE';
    const H1_FOCUS_NOT_FOUND = 'H1_FOCUS_NOT_FOUND';

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
            static::H1_NOT_FOUND => [
                static::STATE_BAD,
                _t(__CLASS__ . '.NOT_FOUND', 'H1_NOT_FOUND')
            ],
            static::H1_FOCUS_NOT_FOUND => [
                static::STATE_BAD,
                _t(__CLASS__ . '.FOCUS_NOT_FOUND', 'H1_FOCUS_NOT_FOUND')
            ],
            static::H1_MULTIPLE => [
                static::STATE_BAD,
                _t(__CLASS__ . '.MULTIPLE', 'H1_MULTIPLE')
            ],
            static::H1_SUCCESS => [
                static::STATE_GOOD,
                _t(__CLASS__ . '.SUCCESS', 'H1_SUCCESS')
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
        $title = $this->getDom()->find('h1');

        if (count($title) == 0) {
            return static::H1_NOT_FOUND;
        } elseif (count($title) > 1) {
            return static::H1_MULTIPLE;
        }

        if ($this->getFocus() && !strstr(strtolower($title->text()), strtolower($this->getFocus()))) {
            return static::H1_FOCUS_NOT_FOUND;
        }

        return static::H1_SUCCESS;
    }
}
