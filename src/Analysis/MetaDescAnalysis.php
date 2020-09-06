<?php
namespace Syntro\Seo\Analysis;

use Syntro\Seo\Seo;

/**
 * Class MetaDescAnalysis
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class MetaDescAnalysis extends Analysis
{
    const META_DESC_NO_FOCUS_KEYWORD = 2; // only checked if the focus keyword has been defined
    const META_DESC_SUCCESS          = 3;
    const META_DESC_TOO_LONG         = 1;
    const META_DESC_TOO_SHORT        = 0;
    const META_DESC_UNSET            = -1;

    /**
     * @return array
     */
    public function responses()
    {
        return [
            static::META_DESC_UNSET
                => [
                    _t(
                        __CLASS__ . '.UNSET',
                        'The meta description has not been set; a potentially unwanted snippet may be taken from the page and displayed instead'
                    ),
                    'danger'
                ],
            static::META_DESC_TOO_SHORT
                => [
                    _t(
                        __CLASS__ . '.TOO_SHORT',
                        'The meta description is too short'
                    ),
                    'warning'
            ],
            static::META_DESC_TOO_LONG
                => [
                    _t(
                        __CLASS__ . '.TOO_LONG',
                        'The meta description is too long'
                    ),
                    'danger'
                ],
            static::META_DESC_NO_FOCUS_KEYWORD
                => [
                    _t(
                        __CLASS__ . '.NO_FOCUS_KEYWORD',
                        'The meta description does not contain the focus keyword'
                    ),
                    'warning'
                ],
            static::META_DESC_SUCCESS
                => [
                    _t(
                        __CLASS__ . '.SUCCESS',
                        'The meta description is perfect!'
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
        $desc    = $this->getPage()->MetaDescription;
        $keyword = $this->getKeyword();

        if (!$desc) {
            return static::META_DESC_UNSET;
        }

        if (strlen($desc) < Seo::GOOGLE_MIN_DESCRIPTION_LENGTH) {
            return static::META_DESC_TOO_SHORT;
        }

        if (strlen($desc) > Seo::GOOGLE_MAX_DESCRIPTION_LENGTH) {
            return static::META_DESC_TOO_LONG;
        }

        if ($keyword && !strstr(strtolower($desc), strtolower($keyword))) {
            return static::META_DESC_NO_FOCUS_KEYWORD;
        }

        return static::META_DESC_SUCCESS;
    }
}
