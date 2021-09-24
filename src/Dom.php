<?php

namespace Syntro\SEO;

use PHPHtmlParser\Dom as PHPDom;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPApplication;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPRequestBuilder;
use SilverStripe\View\Requirements;
use SilverStripe\View\Requirements_Backend;
use SilverStripe\View\SSViewer;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Core\CoreKernel;
use SilverStripe\Core\Kernel;
use SilverStripe\Core\Environment;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Versioned\Versioned;

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
        $dom->loadStr($completeDom->innerHtml);
        foreach ($dom->find('header,footer,nav') as $item) {
            $item->delete();
            unset($item);
        }
        $loadedStrippedDoms[$link] = $dom;
        static::$loadedStrippedDoms = $loadedStrippedDoms;
        return $dom;
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
        $urlParts = parse_url($url);
        if (!empty($urlParts['query'])) {
            parse_str($urlParts['query'], $getVars);
        } else {
            $getVars = [];
        }

        $origRequirements = Requirements::backend();
        Requirements::set_backend(Requirements_Backend::create());
        $origThemes = SSViewer::get_themes();
        $rawThemes = SSViewer::config()->uninherited('themes');
        SSViewer::set_themes($rawThemes);
        $origKernel = Injector::inst()->get(Kernel::class);
        $origKernel->nest();
        // $finally[] = function () use ($origKernel) {
        //     $origKernel->activate();
        // };
        $existingVars = Environment::getVariables();
        // $finally[] = function () use ($existingVars) {
        //     Environment::setVariables($existingVars);
        // };
        $oldReadingMode = null;
        $oldStage = null;
        if (class_exists(Versioned::class)) {
            $oldReadingMode = Versioned::get_reading_mode();
            $oldStage = Versioned::get_stage();
            Versioned::set_stage(Versioned::DRAFT);
            // $finally[] = function () use ($oldReadingMode) {
            //     Versioned::set_reading_mode($oldReadingMode);
            // };
        }



        $request = HTTPRequestBuilder::createFromVariables(
            [
                '_SERVER' => [
                    'REQUEST_URI' => isset($urlParts['path']) ? $urlParts['path'] : '',
                    'REQUEST_METHOD' => 'GET',
                    'REMOTE_ADDR' => '127.0.0.1',
                    'HTTPS' => $urlParts['scheme'] == 'https' ? true : false,
                    'QUERY_STRING' => isset($urlParts['query']) ? $urlParts['query'] : '',
                    'REQUEST_TIME' => DBDatetime::now()->getTimestamp(),
                    'REQUEST_TIME_FLOAT' => (float) DBDatetime::now()->getTimestamp(),
                    'HTTP_HOST' => $urlParts['host'] . (isset($urlParts['port']) ? ':' . $urlParts['port'] : ''),
                    'HTTP_USER_AGENT' => 'silverstripe/seocrawler',
                ],
                '_GET' => $getVars,
                '_POST' => [],
            ],
            ''
        );

        // $kernel = new CoreKernel(BASE_PATH);
        // $app = new HTTPApplication($kernel);
        // $response = $app->handle($request);

        $response = Director::singleton()->handleRequest($request);

        SSViewer::set_themes($origThemes);
        Requirements::set_backend($origRequirements);
        DataObject::singleton()->flushCache();
        $origKernel->activate();
        Environment::setVariables($existingVars);
        if (class_exists(Versioned::class)) {
            Versioned::set_reading_mode($oldReadingMode);
            Versioned::set_stage($oldStage);
        }

        return $response->getBody();
    }
}
