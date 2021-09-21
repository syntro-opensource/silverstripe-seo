<?php

namespace Syntro\SEO\Analysis;

use SilverStripe\ORM\DataObject;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Config;
use Syntro\SEO\Extensions\SEOExtension;
use Syntro\SEO\Analysis\Analysis;

/**
 * checks the title
 */
 class FocusKeywordAnalysis extends Analysis
{

    const FOCUSKEYWORD_NOT_UNIQUE = 'FOCUSKEYWORD_NOT_UNIQUE';
    const FOCUSKEYWORD_SUCCESS    = 'FOCUSKEYWORD_SUCCESS';
    const FOCUSKEYWORD_UNSET      = 'FOCUSKEYWORD_UNSET';

    /**
     * isHidden - if true, this analysis should be hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return $this->getResult() == static::FOCUSKEYWORD_SUCCESS;
    }

    /**
     * getOptions - returns an array containing possible outcomes of this analysis
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            static::FOCUSKEYWORD_NOT_UNIQUE => [
                static::STATE_WARN,
                _t(__CLASS__ . '.NOT_UNIQUE', 'FOCUSKEYWORD_NOT_UNIQUE')
            ],
            static::FOCUSKEYWORD_SUCCESS => [
                static::STATE_GOOD,
                _t(__CLASS__ . '.SUCCESS', 'FOCUSKEYWORD_SUCCESS')
            ],
            static::FOCUSKEYWORD_UNSET => [
                static::STATE_NONE,
                _t(__CLASS__ . '.UNSET', 'FOCUSKEYWORD_UNSET')
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
        if (!$this->getFocus()) {
            return static::FOCUSKEYWORD_UNSET;
        }
        $classes = ClassInfo::subclassesFor(DataObject::class);
        $output  = 0;
        foreach ($classes as $class) {
            if (in_array(SEOExtension::class, $class::config()->uninherited('extensions') ?? [])) {
                $output += $class::get()->filter('SEOFocusKeyword', $this->getFocus())->count();
            }
        }
        if ($output > 1) {
            return static::FOCUSKEYWORD_NOT_UNIQUE;
        }

        return static::FOCUSKEYWORD_SUCCESS;
    }


}
