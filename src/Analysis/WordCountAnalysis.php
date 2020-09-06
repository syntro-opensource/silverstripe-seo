<?php
namespace Syntro\Seo\Analysis;

use Syntro\Seo\Seo;

/**
 * Class WordCountAnalysis
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class WordCountAnalysis extends Analysis
{
    const WORD_COUNT_ABOVE_MIN = 1;
    const WORD_COUNT_BELOW_MIN = 0;

    /**
     * @return int
     */
    public function getWordCount()
    {
        return count(array_filter(explode(' ', $this->getContent())));
    }

    /**
     * @return array
     */
    public function responses()
    {
        return [
            static::WORD_COUNT_BELOW_MIN => [
                _t(
                    __CLASS__ . '.BELOW_MIN',
                    'The content of this page contains {count} words which is less than the {min} recommended minimum',
                    [
                        'count' => $this->getWordCount(),
                        'min' => Seo::GOOGLE_MIN_CONTENT_LENGTH
                    ]
                ),
                'warning'
            ],
            static::WORD_COUNT_ABOVE_MIN => [
                _t(
                    __CLASS__ . '.ABOVE_MIN',
                    'The content of this page contains {count} words which is above the {min} recommended minimum',
                    [
                        'count' => $this->getWordCount(),
                        'min' => Seo::GOOGLE_MIN_CONTENT_LENGTH
                    ]
                ),
                'success'
            ],
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
        $wordCount = $this->getWordCount();

        if ($wordCount < 300) {
            return static::WORD_COUNT_BELOW_MIN;
        }

        return static::WORD_COUNT_ABOVE_MIN;
    }
}
