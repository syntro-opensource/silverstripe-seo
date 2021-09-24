<?php

namespace Syntro\SEO\Analysis;

use Syntro\SEO\Analysis\Analysis;

/**
 * checks the title
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class TitleAnalysis extends Analysis
{
    const GOOGLE_MAX_TITLE_LENGTH = 70;
    const GOOGLE_OPT_TITLE_LENGTH = 40;
    const GOOGLE_MIN_TITLE_LENGTH = 30;

    const TITLE_FOCUS_KEYWORD_POSITION = 'FOCUS_KEYWORD_POSITION';
    const TITLE_IS_HOME = 'IS_HOME';
    const TITLE_NO_FOCUS_KEYWORD = 'NO_FOCUS_KEYWORD';
    const TITLE_OK_BUT_SHORT = 'OK_BUT_SHORT';
    const TITLE_SUCCESS = 'SUCCESS';
    const TITLE_TOO_LONG = 'TOO_LONG';
    const TITLE_TOO_SHORT = 'TOO_SHORT';

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
        $dom = $this->getDom();
        return [
            self::TITLE_FOCUS_KEYWORD_POSITION => [
                self::STATE_WARN,
                _t(__CLASS__ . '.FOCUS_KEYWORD_POSITION', 'TITLE_FOCUS_KEYWORD_POSITION')
            ],
            self::TITLE_IS_HOME => [
                self::STATE_BAD,
                _t(__CLASS__ . '.IS_HOME', 'TITLE_IS_HOME', [
                    'metatitle' => $dom->find('title')->text(),
                    'title' => "Home"
                ])
            ],
            self::TITLE_NO_FOCUS_KEYWORD => [
                self::STATE_BAD,
                _t(__CLASS__ . '.NO_FOCUS_KEYWORD', 'TITLE_NO_FOCUS_KEYWORD')
            ],
            self::TITLE_OK_BUT_SHORT => [
                self::STATE_GOOD,
                _t(__CLASS__ . '.OK_BUT_SHORT', 'TITLE_OK_BUT_SHORT', ['min' => self::GOOGLE_MIN_TITLE_LENGTH])
            ],
            self::TITLE_SUCCESS => [
                self::STATE_GOOD,
                _t(__CLASS__ . '.SUCCESS', 'TITLE_SUCCESS')
            ],
            self::TITLE_TOO_LONG => [
                self::STATE_BAD,
                _t(__CLASS__ . '.TOO_LONG', 'TITLE_TOO_LONG')
            ],
            self::TITLE_TOO_SHORT => [
                self::STATE_BAD,
                _t(__CLASS__ . '.TOO_SHORT', 'TITLE_TOO_SHORT')
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
        $title = $dom->find('title')->text();
        $focus = $this->getFocus();
        $homeString = _t(__CLASS__ . '.HOME', 'home');
        if (strtolower($title) == $homeString ||
            strpos(strtolower($title), $homeString) === 0
        ) {
            return static::TITLE_IS_HOME;
        }

        if (strlen($title) < static::GOOGLE_MIN_TITLE_LENGTH) {
            return static::TITLE_TOO_SHORT;
        }

        if (strlen($title) > static::GOOGLE_MAX_TITLE_LENGTH) {
            return static::TITLE_TOO_LONG;
        }

        if ($focus && !strstr(strtolower($title), strtolower($focus))) {
            return static::TITLE_NO_FOCUS_KEYWORD;
        }

        if ($focus && strtolower(substr($title, 0, strlen($focus))) !== strtolower($focus)) {
            return static::TITLE_FOCUS_KEYWORD_POSITION;
        }

        if (strlen($title) < static::GOOGLE_OPT_TITLE_LENGTH) {
            return static::TITLE_OK_BUT_SHORT;
        }

        return static::TITLE_SUCCESS;
    }
}
