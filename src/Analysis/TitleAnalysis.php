<?php
namespace Syntro\Seo\Analysis;

use Syntro\Seo\Seo;

/**
 * Class TitleAnalysis
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class TitleAnalysis extends Analysis
{
    const TITLE_FOCUS_KEYWORD_POSITION = 4;
    const TITLE_IS_HOME                = -1;
    const TITLE_NO_FOCUS_KEYWORD       = 3; // only checked if the focus keyword has been defined
    const TITLE_OK_BUT_SHORT           = 1;
    const TITLE_SUCCESS                = 5;
    const TITLE_TOO_LONG               = 2;
    const TITLE_TOO_SHORT              = 0;

    /**
     * @return array
     */
    public function responses()
    {
        $metaTitle = $this->getDom()->find('title')->text();
        $pageTitle = $this->getPage()->Title;
        return [
            static::TITLE_IS_HOME => [
                _t(
                    __CLASS__ . '.IS_HOME',
                    'The page title should be changed from "{metatitle}"; that title almost always reduces click-through rate. Please retain "{title}" as the Navigation Label, however.',
                    [
                        'metatitle' => $metaTitle,
                        'title' => $pageTitle,
                    ]
                ),
                'danger'
            ],
            static::TITLE_TOO_SHORT => [
                _t(
                    __CLASS__ . '.TOO_SHORT',
                    'The page title is too short'
                ),
                 'danger'
             ],
            static::TITLE_OK_BUT_SHORT => [
                _t(
                    __CLASS__ . '.OK_BUT_SHORT',
                    'The page title is a little short but is above the absolute character minimum of {min} characters.',
                    [
                        'min' => Seo::GOOGLE_MIN_TITLE_LENGTH
                    ]
                ),
                'warning'
            ],
            static::TITLE_TOO_LONG => [
                _t(
                    __CLASS__ . '.TOO_LONG',
                    'The page title is too long'
                ),
                'danger'
            ],
            static::TITLE_NO_FOCUS_KEYWORD => [
                _t(
                    __CLASS__ . '.NO_FOCUS_KEYWORD',
                    'The page title does not contain the focus keyword'
                ),
                'warning'
            ],
            static::TITLE_FOCUS_KEYWORD_POSITION => [
                _t(
                    __CLASS__ . '.FOCUS_KEYWORD_POSITION',
                    'The page title contains the focus keyword but is not at the beginning; consider moving it to the beginning'
                ),
                'warning'
            ],
            static::TITLE_SUCCESS => [
                _t(
                    __CLASS__ . '.SUCCES',
                    'The page title is between the recommended {min} character count and the recommended {max} character maximum',
                    [
                        'min' => Seo::GOOGLE_MIN_TITLE_LENGTH,
                        'max' => SEO::GOOGLE_MAX_TITLE_LENGTH
                    ]
                ),
                'success'
            ]
        ];
    }

    /**
     * You must override this in your subclass and perform your own checks. An integer must be returned
     * that references an index of the array you return in your response() method override in your subclass.
     *
     * @return int
     */
    public function run()
    {
        $metaTitle = $this->getDom()->find('title')->text();
        $pageTitle = $this->getPage()->Title;
        $keyword = $this->getKeyword();

        if (
            strtolower($pageTitle) == 'home' ||
            strtolower($pageTitle) == 'startseite'
        ) {
            return static::TITLE_IS_HOME;
        }

        if (strlen($metaTitle) < Seo::GOOGLE_MIN_TITLE_LENGTH) {
            return static::TITLE_TOO_SHORT;
        }

        if (strlen($metaTitle) < Seo::GOOGLE_OPT_TITLE_LENGTH) {
            return static::TITLE_OK_BUT_SHORT;
        }

        if (strlen($metaTitle) > SEO::GOOGLE_MAX_TITLE_LENGTH) {
            return static::TITLE_TOO_LONG;
        }

        if ($keyword && !strstr(strtolower($metaTitle), strtolower($keyword))) {
            return static::TITLE_NO_FOCUS_KEYWORD;
        }

        if ($keyword && strtolower(substr($metaTitle, 0, strlen($keyword))) !== strtolower($keyword)) {
            return static::TITLE_FOCUS_KEYWORD_POSITION;
        }

        return 5;
    }
}
