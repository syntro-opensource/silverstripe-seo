<?php
namespace Syntro\SEOMeta\Preview;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\View\ViewableData;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Control\Session;

use PHPHtmlParser\Dom;

/**
 * A preview handles scanning a Page for Information to display a
 * preview of how this Page could be presented to a user on specific platforms.
 *
 * These previews can then be included in the CMS to give the User an
 * Understanding of how the currrently viewed page is displayed (eg. on Facebook)
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class Preview extends ViewableData
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

    /**
     * __construct - constructor
     *      
     * @param  SiteTree $page the record to be tried
     * @return void
     */
    function __construct($page)
    {
        $this->setPage($page);
        $dom = new Dom;
        $dom->loadFromUrl($page->AbsoluteLink());
        $this->setDom($dom);
    }



    /**
     * Highlights parts of the $haystack that match the focus keyword as a whole, case insensitive
     *
     * @param $haystack
     * @param $needle
     *
     * @return mixed
     */
    public function highlight($haystack, $needle)
    {
        if (!$needle) {
            return $haystack;
        }

        return preg_replace('/\b(' . $needle . ')\b/i', '<strong>$0</strong>', strip_tags($haystack));
    }

    /**
     * forTemplate - render function
     *
     * @return
     */
    public function forTemplate()
    {
        return $this->renderWith(
            $this->getViewerTemplates()
        );
    }
}
