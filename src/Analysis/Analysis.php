<?php

namespace Syntro\SEO\Analysis;

use SilverStripe\View\ViewableData;
use Syntro\SEO\Dom;
use PHPHtmlParser\Dom as PHPDom;

/**
 * allows the analysis of a link in response to a keyword
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
abstract class Analysis extends ViewableData
{

    const STATE_GOOD    = 1;
    const STATE_NONE    = 0;
    const STATE_WARN    = -1;
    const STATE_BAD     = -2;
    const STATE_CAUTION = -3;
    const STATE_INVALID = -4;

    const STATE_COLOR_MAP = [
        '1' => '#58d632',
        '0' => '#bebebe',
        '-1' => '#feae26',
        '-2' => '#f62236',
    ];

    const STATE_ICON_MAP = [
        '1' => '🟢',
        '0' => '⚪️',
        '-1' => '🟡',
        '-2' => '🔴',
        '-3' => '⚠️',
        '-4' => '❌'
    ];

    protected $link;

    protected $keyword;

    protected $result = null;
    protected $hidden = null;

    /**
     * Ensures that the methods are wrapped in the correct type and
     * values are safely escaped while rendering in the template.
     * @config
     * @var array
     */
    private static $casting = [
        'Icon' => 'HTMLText',
        'Hint' => 'HTMLText'
    ];

    /**
     * __construct - creates an analysis
     *
     * @param  string $link    the link to the page this analysis should check
     * @param  string $keyword the keyword
     * @return void
     */
    function __construct($link, $keyword = null)
    {
        parent::__construct();
        $this->link = $link;
        $this->keyword = $keyword;
    }

    /**
     * getDom - get the dom of the page to be analyzed
     *
     * @return PHPDom
     */
    public function getDom()
    {
        $dom = Dom::getDom($this->link);
        return $dom;
    }

    /**
     * getStrippedDom - returns the dom stripped of footer, header and nav components
     *
     * @return PHPDom
     */
    public function getStrippedDom()
    {
        $dom = Dom::getStrippedDom($this->link);
        return $dom;
    }

    /**
     * getPageContent - returns the content as a string
     *
     * @return string
     */
    public function getPageContent()
    {
        $content = Dom::getTextualRepresentation($this->link);
        return $content;
    }

    /**
     * getFocus - return the focus this analysis should consider
     *
     * @return string
     */
    public function getFocus()
    {
        return $this->keyword;
    }

    /**
     * rememberedResult - returns the cached result
     *
     * @return string
     */
    public function rememberedResult()
    {
        if (!$this->result) {
            $this->result = $this->getResult();
        }
        return $this->result;
    }

    /**
     * getRememberedHidden - returns the cached hidden value
     *
     * @return bool
     */
    public function getRememberedHidden()
    {
        if (!$this->hidden || $this->hidden !== false) {
            $this->hidden = $this->isHidden();
        }
        return $this->hidden;
    }

    /**
     * getIcon - returns the icon to display
     *
     * @return string
     */
    public function getIcon()
    {
        $state = $this->getState();
        if (!isset(self::STATE_COLOR_MAP[strval($state)])) {
            throw new \Exception("Tha state '$state' returned from " . __CLASS__ . " is not valid.", 1);
        }
        $color =  self::STATE_COLOR_MAP[strval($state)];
        return <<<HTML
            <span style="height: .8rem; width: .8rem; display: block; background-color: $color; border-radius: .8rem;"></span>
        HTML;
        // $state = $this->getState();
        // if (!isset(self::STATE_ICON_MAP[strval($state)])) {
        //     throw new \Exception("Tha state '$state' returned from ".__CLASS__." is not valid.", 1);
        // }
        // return self::STATE_ICON_MAP[strval($state)];
    }

    /**
     * getOption - returns the option this test has found to apply
     *
     * @return array
     */
    public function getOption()
    {
        $options = $this->getOptions();
        $result = $this->rememberedResult();
        if (!isset($options[$result])) {
            throw new \Exception("Result '$result' is not valid for " . __CLASS__ . ".", 1);
        }
        $option = $options[$result];
        if (count($option) !== 2) {
            throw new \Exception("option for '$result' is not formatted correctly", 1);
        }
        return $option;
    }

    /**
     * getState - returns the state of this analysis
     *
     * @return int|string
     */
    public function getState()
    {
        return $this->getOption()[0];
    }

    /**
     * getHint - returns the hint text for this analysis
     *
     * @return string
     */
    public function getHint()
    {
        return $this->getOption()[1];
    }

    /**
     * forTemplate - returns a string to render to a template
     *
     * @return string
     */
    public function forTemplate()
    {
        return $this->renderWith(__CLASS__);
    }

    /**
     * isHidden - if true, this analysis should be hidden
     *
     * @return boolean
     */
    abstract public function isHidden();

    /**
     * getOptions - returns an array containing possible outcomes of this analysis
     *
     * @return array
     */
    abstract public function getOptions();

    /**
     * getResult - returns the result of this analysis. The result must correspond
     * to a key in the getOptions() array.
     *
     * @return int|string
     */
    abstract public function getResult();
}
