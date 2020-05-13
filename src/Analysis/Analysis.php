<?php

namespace Syntro\Seo\Analysis;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\View\ArrayData;
use PHPHtmlParser\Dom;


/**
 *
 */
abstract class Analysis
{

    use Injectable, Configurable;

    /**
     * @var Dom
     */
    protected $Dom;

    /**
     * @var SiteTree
     */
    protected $page;

    /**
     * @var int
     */
    protected $result;

    /**
     * One of: default, danger, warning or success
     *
     * @var string
     */
    protected $resultLevel;

    /**
     * Allows you to hide certain levels (default, danger, success) from appearing in the content analysis.
     * You can specif this on a per analysis basis via YML or add the below to your own analysis instead
     *
     * @config
     * @var array
     */
    private static $hidden_levels = [];

    private static $indicator_levels = [
        'hidden',
        'default',
        'warning',
        'danger',
        'success'
    ];

    /**
     * Analysis constructor.
     *
     * @param SiteTree $page
     */
    public function __construct(SiteTree $page, Dom $dom)
    {
        $this->setPage($page);
        $this->Dom = $dom;
    }

    /**
     * @return SiteTree
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getKeyword()
    {
        return strtolower($this->getPage()->SEOFocusKeyword);
    }

    /**
     * @param SiteTree $page
     * @return $this
     */
    public function setPage(SiteTree $page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return int
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Fetches the rendered content from the dom parser. This is why it's important that your templates are semantically
     * correct. `<div>` tags should be used for layout and positioning purposes and using `<p>` tags for content is
     * semantically correct. Semantically correct pages tend to rank higher in search engines for various reasons (such
     * as how effectively crawlers parse your website etc.).
     *
     * @return string
     */
    public function getContent()
    {
        $dom = $this->getDom();
        foreach ($dom->find('header,footer,nav') as $item) {
            $item->delete();
            unset($item);
        }
        $output = [];
        foreach ($dom->find('p,h1,h2,h3,h4,h5,h6,div') as $item) {
            $output[] = strip_tags(html_entity_decode($item->text()));
        }

        $output = array_filter($output);
        return implode(' ', $output);
    }

    /**
     * @return Dom
     */
    public function getDom()
    {
        return $this->Dom;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return ArrayData
     */
    public function inspect()
    {
        $result = $this->run();

        if (!is_numeric($result)) {
            throw new \InvalidArgumentException('Expected integer for response, got ' . gettype($result) . ' instead');
        }
        if (empty($responses = $this->responses())) {
            throw new \InvalidArgumentException('Expected responses() to return a list of possible responses, got '
                . '[]'
                . ' instead');
        }
        if (!isset($responses[$result])) {
            throw new \InvalidArgumentException(sprintf(
                'Expected %s to be a key of the array that responses() returns, except the key %s does not exist',
                $result,
                $result
            ));
        }
        if (count($responses[$result]) !== 2) {
            throw new \InvalidArgumentException(sprintf(
                'Expected the response for result %s to be an array containing two items: ' .
                'first is the message & second is the indicator status: danger, warning, success, default',
                $result
            ));
        }
        if (!in_array($responses[$result][1], $this->config()->get('indicator_levels'))) {
            throw new \InvalidArgumentException(sprintf(
                'The specified indicator (%s) in the response for key %s is not a valid level, valid levels are: %s',
                $responses[$result][1],
                $result,
                implode(', ', $this->config()->get('indicator_levels'))
            ));
        }
        $this->result      = $result;
        $this->resultLevel = $responses[$result][1];

        return ArrayData::create([
            'Analysis' => static::class,
            'Result'   => $result,
            'Response' => $responses[$result][0],
            'Level'    => $this->resultLevel,
            'Hidden'   => $this->resultLevel === 'hidden'
                ? true
                : in_array($this->resultLevel, $this->config()->get('hidden_levels'))
        ]);
    }

    /**
     * All analyses must override the `responses()` method to provide response messages and the response level (which
     * is used for the indicator). The returned array must contain sub-arrays like this:
     * [
     *   'Hoorah!!! "Hello World!" appears in the page title',
     *   'success'
     * ]
     * `run()` should return an integer that matches a key in the array that `responses()` returns, for example if
     * `run()` were to return `1`, then using the above example the message displayed would be `Hoorah!!! "Hello
     * World!" appears in the page title` with a indicator level of `success`. The available indicator levels are:
     * `default`, `danger`, `warning`, `success` which are grey, red, orange and green respectively.
     *
     * @return array
     */
    public function responses()
    {
        return [];
    }

    /**
     * You must override this in your subclass and perform your own checks. An integer must be returned
     * that references an index of the array you return in your response() method override in your subclass.
     *
     * @return int
     */
    public function run()
    {
        throw new \RuntimeException(srintf(
            'You must override the run method in %s and return an integer as a response that references '
            . 'a key in your array that your responses() override returns',
            static::class
        ));
    }
}
