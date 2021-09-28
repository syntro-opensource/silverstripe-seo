<?php

namespace Syntro\SEO\Analysis;

use Syntro\SEO\Analysis\Analysis;

/**
 * checks the content length
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class ContentWordCountAnalysis extends Analysis
{
    const GOOGLE_OPT_CONTENT_LENGTH = 300;

    const CONTENT_WORDCOUNT_ABOVE_MIN = 'CONTENT_WORDCOUNT_ABOVE_MIN';
    const CONTENT_WORDCOUNT_BELOW_MIN = 'CONTENT_WORDCOUNT_BELOW_MIN';

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
            static::CONTENT_WORDCOUNT_ABOVE_MIN => [
                static::STATE_GOOD,
                _t(__CLASS__ . '.WORDCOUNT_ABOVE_MIN', 'CONTENT_WORDCOUNT_ABOVE_MIN ({count} of {min})', ['count' => $this->getWordCount(), 'min' => self::GOOGLE_OPT_CONTENT_LENGTH])
            ],
            static::CONTENT_WORDCOUNT_BELOW_MIN => [
                static::STATE_WARN,
                _t(__CLASS__ . '.WORDCOUNT_BELOW_MIN', 'CONTENT_WORDCOUNT_BELOW_MIN ({count} of {min})', ['count' => $this->getWordCount(), 'min' => self::GOOGLE_OPT_CONTENT_LENGTH])
            ],
        ];
    }

    /**
     * getWordCount - counts the words
     *
     * @return int
     */
    public function getWordCount()
    {
        $content = $this->getStrippedDom();
        $body = $content->find('p', 0);
        return $body ? count(array_filter(explode(' ', $body->text(true)))) : 0;
    }

    /**
     * getResult - returns the result of this analysis. The result must correspond
     * to a key in the getOptions() array.
     *
     * @return int|string
     */
    public function getResult()
    {
        if ($this->getWordCount() < self::GOOGLE_OPT_CONTENT_LENGTH) {
            return static::CONTENT_WORDCOUNT_BELOW_MIN;
        }

        return static::CONTENT_WORDCOUNT_ABOVE_MIN;
    }
}
