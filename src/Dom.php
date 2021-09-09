<?php

namespace Syntro\SEO;

use PHPHtmlParser\Dom as PHPDom;
use SilverStripe\Control\Director;

/**
 * Helper class used to retrieve the DOM of a page from its link and remembering
 * the value
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class Dom
{

    static protected $loadedDoms = [];

    static protected $loadedStrippedDoms = [];

    /**
     * __construct - Barebone constructor
     *
     * @return void
     */
    function __construct() {
        throw new \Exception(__CLASS__." should only be used statically.", 1);

    }



    /**
     * getDom - returns the dom for the given URL
     *
     * @param  {type} $link description
     * @return {type}       description
     */
    public static function getDom($link)
    {
        $loadedDoms = static::$loadedDoms;
        if (isset($loadedDoms[$link])) {
            return $loadedDoms[$link];
        }

        $dom = new PHPDom;
        $url = Director::absoluteURL($link);
        $dom->loadStr(file_get_contents($url));
        $loadedDoms[$link] = $dom;
        static::$loadedDoms = $loadedDoms;
        return $dom;
    }

    /**
     * getStrippedDom - returns a dom instance which has been stripped from header,
     * nav and footer
     *
     * @param  {type} $link description
     * @return {type}       description
     */
    public static function getStrippedDom($link)
    {
        $loadedStrippedDoms = static::$loadedStrippedDoms;
        if (isset($loadedStrippedDoms[$link])) {
            return $loadedStrippedDoms[$link];
        }

        $completeDom = static::getDom($link);
        $dom = new PHPDom;
        $dom->loadStr($completeDom->__toString());
        foreach ($dom->find('header,footer,nav') as $item) {
            $item->delete();
            unset($item);
        }
        $loadedStrippedDoms[$link] = $dom;
        static::$loadedStrippedDoms = $loadedStrippedDoms;
        return $dom;
    }
}
