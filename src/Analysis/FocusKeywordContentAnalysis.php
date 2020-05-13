<?php

namespace Syntro\Seo\Analysis;

/**
 * Class FocusKeywordContentAnalysis
 */
class FocusKeywordContentAnalysis extends Analysis
{
    const FOCUS_KEYWORD_NOT_FOUND = 0;
    const FOCUS_KEYWORD_SUCCESS   = 1;
    const FOCUS_KEYWORD_UNSET     = -1;

    private static $hidden_levels = [
        'default'
    ];

    /**
     *
     */
    public function findOccurrences()
    {
        $content = $this->getContent();

        if (!strlen($content) || !$this->getKeyword()) {
            return 0;
        }

        return substr_count(strtolower($content), $this->getKeyword());
    }



    /**
     * @return array
     */
    public function responses()
    {
        return [
            static::FOCUS_KEYWORD_UNSET     => [
                'The focus keyword has not been set; consider setting this to improve content analysis',
                'default'
            ],
            static::FOCUS_KEYWORD_NOT_FOUND => [
                'The focus keyword was not found in the content of this page',
                'danger'
            ],
            static::FOCUS_KEYWORD_SUCCESS   => [
                'The focus keyword was found <strong>' . $this->findOccurrences() . '</strong> times.',
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
        if (!$this->getKeyword()) {
            return static::FOCUS_KEYWORD_UNSET;
        }

        if (!strstr(strtolower($this->getContent()), $this->getKeyword())) {
            return static::FOCUS_KEYWORD_NOT_FOUND;
        }

        return static::FOCUS_KEYWORD_SUCCESS;
    }
}
