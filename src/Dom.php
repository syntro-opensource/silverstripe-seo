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
    function __construct()
    {
        throw new \Exception(__CLASS__ . " should only be used statically.", 1);
    }



    /**
     * getDom - returns the dom for the given URL
     *
     * @param  string $link the link to fetch
     * @return PHPDom
     */
    public static function getDom($link)
    {
        $loadedDoms = static::$loadedDoms;
        if (isset($loadedDoms[$link])) {
            return $loadedDoms[$link];
        }
        // throw new \Exception(json_encode($_SERVER['HTTP_COOKIE']), 1);
        $body = static::loadContent($link);
        $dom = new PHPDom;
        $dom->loadStr($body);
        // throw new \Exception($body, 1);
        //
        // $dom = new PHPDom;
        // $url = Director::absoluteURL($link);
        //
        // $dom->loadStr(file_get_contents($url.'?stage=Stage'));
        $loadedDoms[$link] = $dom;
        static::$loadedDoms = $loadedDoms;
        return $dom;
    }

    /**
     * getStrippedDom - returns a dom instance which has been stripped from header,
     * nav and footer
     *
     * @param  string $link the link to check
     * @return PHPDom
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

    /**
     * getTextualRepresentation - returns the content of the page as a single
     * string, retaining spaces between tags
     *
     * @param  string $link the link to check
     * @return string
     */
    public static function getTextualRepresentation($link)
    {
        $dom = static::getStrippedDom($link);
        $body = $dom->find('body');
        $string = $body->innerHtml();
        $spaceString = str_replace('<', ' <', $string);
        $doubleSpace = strip_tags($spaceString);
        $singleSpace = str_replace('  ', ' ', $doubleSpace);
        return $singleSpace;
    }

    /**
     * loadContent - loads the content via a mock request (from the draft stage
     * if versioning is enabled).
     *
     * @param  string $url the url to load
     * @return string
     */
    private static function loadContent($url)
    {
        if (Director::is_relative_url($url)) {
            $url = Director::absoluteURL($url);
        }
        $result = file_get_contents($url);
        if (!$result) {
            throw new \Exception("could not fetch content for {$url}.", 1);
        }
        return $result;
    }
}
