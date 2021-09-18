<?php

namespace Syntro\SEO\Forms;

use SilverStripe\Forms\DatalessField;
use SilverStripe\Core\ClassInfo;
use Syntro\Seo\Analysis\Analysis;
use SilverStripe\ORM\ArrayList;

/**
 * Renders a Field which analyzes the content of a Page using a keyword
 */
class KeywordAnalysisField extends DatalessField
{
    protected $link = null;
    protected $focus = null;

    function __construct($name, $title, $link, $focus = null)
    {
        parent::__construct($name, $title);
        $this->link = $link;
        $this->focus = $focus;
    }

    public function getAnalyses()
    {
        $classes = ClassInfo::subclassesFor(Analysis::class);
        $output  = [];

        /** @var Analysis $class */
        foreach ($classes as $class) {
            if ($class == Analysis::class) {
                continue;
            }
            $output[] = $class::create($this->link,$this->focus);
        }

        if (count($output) == 0) {
            return 'No applicable tests found...';
        }

        uasort($output, function ($aAnalysis, $bAnalysis) {
            $a = $aAnalysis->getState();
            $b = $bAnalysis->getState();
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });
        return ArrayList::create($output)->filterByCallback(function ($value) {
            return !$value->getRememberedHidden();
        });
    }
}
