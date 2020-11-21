<?php
namespace Syntro\Seo\Forms;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Core\ClassInfo;
use Syntro\Seo\Analysis\Analysis;
use SilverStripe\View\Requirements;

use PHPHtmlParser\Dom;

/**
 * Handles the Display of the
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class SeoAnalysisField extends LiteralField
{

    use Injectable, Configurable;

    /**
     * @var SiteTree
     */
    protected $page;

    /**
     * @var Dom
     */
    protected $dom;


    /**
     * __construct - construct a Field
     *
     * @param  string   $name name of the field
     * @param  SiteTree $page the analysed record
     */
    function __construct($name, SiteTree $page)
    {
        $this->setPage($page);

        parent::__construct($name, ArrayData::create([
            'FieldTitle' => _t(__CLASS__ . '.Title', 'SEO Analysis'),
            'RightTitle' => _t(
                __CLASS__ . '.Description',
                'We have found these points on this page. Please try to correct any errors.'
            ),
            'Results'    => $this->runAnalyses(),
        ])->renderWith(self::class));
    }

    /**
     * Fetches a list of all Analysis subclasses
     *
     * @return array
     */
    public function getAnalyses()
    {
        $classes = ClassInfo::subclassesFor(Analysis::class);
        $output  = [];

        /** @var Analysis $class */
        foreach ($classes as $class) {
            if ($class == Analysis::class) {
                continue;
            }

            $output[] = $class;
        }

        return $output;
    }

    /**
     * Runs all analyses and returns an ArrayList
     *
     * @return ArrayList
     */
    public function runAnalyses()
    {
        $analyses = $this->getAnalyses();
        $output   = ArrayList::create([]);

        foreach ($analyses as $analysisClass) {
            /** @var Analysis $analysis */
            $analysis = $analysisClass::create($this->getPage(), $this->getDom());
            $output->push($analysis->inspect());
        }

        return $output;
    }

    /**
     * setPage
     *
     * @param  SiteTree $value the page
     * @return SeoAnalysisField
     */
    public function setPage($value)
    {
        $this->page = $value;
        return $this;
    }

    /**
     * getPage
     *
     * @return SiteTree
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * setDom
     *
     * @param  Dom $value the dom
     * @return SeoAnalysisField
     */
    public function setDom($value)
    {
        $this->dom = $value;
        return $this;
    }

    /**
     * getDom
     *
     * @return Dom
     */
    public function getDom()
    {
        if(!isset($this->dom) || is_null($this->dom)) {
          $dom = new Dom;
          Requirements::clear();
          $dom->loadStr($this->getPage()->renderWith($this->getPage()->getViewerTemplates()));
          Requirements::restore();
          $this->setDom($dom);
        }
        return $this->dom;
    }
}
