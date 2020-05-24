<?php
namespace Syntro\Seo\Analysis;

/**
 * Class FocusKeywordUniqueAnalysis
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class FocusKeywordUniqueAnalysis extends Analysis
{
    const FOCUS_KEYWORD_INUSE   = 0;
    const FOCUS_KEYWORD_SUCCESS = 1;
    const FOCUS_KEYWORD_UNSET   = -1;


    /**
     *
     * @return array
     */
    public function responses()
    {
        return [
            static::FOCUS_KEYWORD_UNSET   => [
                'The focus keyword has not been set, consider setting this to improve content analysis',
                'default'
            ],
            static::FOCUS_KEYWORD_INUSE   => [
                'The focus keyword you want this page to rank for is already being used on another page; ' .
                'consider changing that if you truly want this page to rank',
                'danger'
            ],
            static::FOCUS_KEYWORD_SUCCESS => ['The focus keyword has never been used before—nice!', 'success']
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

        /**
         * @var null|\Page
         */
        $page_with_same_keyword = \Page::get()->filter(['FocusKeyword' => $this->getKeyword(), 'ID:not' => $this->getPage()->ID])->first();
        if ($page_with_same_keyword) {
            return static::FOCUS_KEYWORD_INUSE;
        }

        return static::FOCUS_KEYWORD_SUCCESS;
    }
}
