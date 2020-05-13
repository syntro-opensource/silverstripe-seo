<?php
namespace Syntro\SEOMeta\Forms;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Core\ClassInfo;
use Syntro\SEOMeta\Analysis\Analysis;

use PHPHtmlParser\Dom;

/**
 * Handles the Display of the
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
     * @param  string $name          name of the field
     * @param  string $title         title of the field
     * @param  SiteTree $page the analysed record
     * @return {type}                description
     */
    function __construct($name, $title, SiteTree $page)
    {
        $this->setPage($page);
        $dom = new Dom;
        $dom->loadFromUrl($page->AbsoluteLink());
        $this->setDom($dom);

        parent::__construct($name, ArrayData::create([
            'FieldTitle' => $title,
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
            if ($class === Analysis::class) {
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
     * @return Preview
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
     * @return Preview
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
        return $this->dom;
    }
}
