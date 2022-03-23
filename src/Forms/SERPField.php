<?php

namespace Syntro\SEO\Forms;

use SilverStripe\Forms\DatalessField;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\ArrayList;
// use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\ORM\FieldType\DBHTMLText;
use Syntro\SEO\Dom;
use SilverStripe\Control\Director;

/**
 * A Preview of the content in Google
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class SERPField extends DatalessField
{

    const GOOGLE_MAX_TITLE_LENGTH = 70;
    const GOOGLE_MAX_DESCRIPTION_LENGTH = 160;

    /**
     * @var string
     */
    protected $link = null;

    /**
     * @var string
     */
    protected $focus = null;

    /**
     * Ensures that the methods are wrapped in the correct type and
     * values are safely escaped while rendering in the template.
     * @var array
     */
    private static $casting = [
        'MetaDescription' => 'HTMLVarchar'
    ];

    /**
     * __construct - constructor
     *
     * @param  string $name  the name of the field
     * @param  string $title = null the title of the field
     * @param  string $link  = '/' the link to analyze
     * @param  string $focus = null an optional focus keyword
     * @return void
     */
    function __construct($name, $title = null, $link = '/', $focus = null)
    {
        parent::__construct($name, $title);
        $this->link = $link;
        $this->focus = $focus;
        // return parent::__construct($name, ArrayData::create([
        //     'MetaTitle' => $this->getMetaTitle($dom, $focus),
        //     'MetaDesc' => $this->getMetaDescription($dom, $focus),
        //     'BaseURL' => Director::host(),
        //     'Crumbs' => $this->getCrumbs($link),
        // ])->renderWith(self::class));
    }

    /**
     * getMetaTitle - returns the meta title detected in the page
     *
     * @return string
     */
    public function getMetaTitle()
    {
        $dom = Dom::getDom($this->link);
        $title = $dom->find('title', 0);
        $title = !is_null($title)
            ? $title->text()
            : null;
        $title = DBHTMLText::create('Title')
            ->setValue($title)
            ->LimitCharacters(self::GOOGLE_MAX_TITLE_LENGTH, '...');
        if ($title && $this->focus) {
            $title = $this->highlight($title, $this->focus);
        }
        return DBHTMLText::create('Title')->setValue($title);
    }

    /**
     * getHostCrumb - returns the first crumb
     *
     * @return string
     */
    public function getHostCrumb()
    {
        return Director::host();
    }

    /**
     * getMetaDescription - returns the descriptionthat google will show
     *
     * @return string
     */
    public function getMetaDescription()
    {
        $dom = Dom::getStrippedDom($this->link);
        $description = $dom->find('meta[name=description]', 0);
        $description = !is_null($description)
            ? $description->getAttributes()['content']
            : null;
        $body = Dom::getTextualRepresentation($this->link);


        if ($description && $this->contains($description, $this->focus)) {
            $description = DBHTMLText::create('Description')
                ->setValue($description)
                ->LimitCharacters(self::GOOGLE_MAX_DESCRIPTION_LENGTH, '...');
        } elseif ($body && $this->contains($body, $this->focus)) {
            $description = DBHTMLText::create('Description')
                ->setValue($body)
                ->ContextSummary(self::GOOGLE_MAX_DESCRIPTION_LENGTH, $this->focus, false, '', '...');
        } elseif ($description) {
            $description = DBHTMLText::create('Description')
                ->setValue($description)
                ->LimitCharacters(self::GOOGLE_MAX_DESCRIPTION_LENGTH, '...');
        } else {
            $description = DBHTMLText::create('Description')
                ->setValue($body)
                ->LimitCharacters(self::GOOGLE_MAX_DESCRIPTION_LENGTH, '...');
        }
        if ($description && $this->focus) {
            $description = $this->highlight($description, $this->focus);
        }
        return DBHTMLText::create('Title')->setValue($description);
    }

    /**
     * Highlights parts of the $haystack that match the focus keyword as a whole, case insensitive
     *
     * @param string $haystack what to search through
     * @param string $needle   what to search for
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
     * Checks if a string is present in a haystack
     *
     * @param string $haystack what to search through
     * @param string $needle   what to search for
     *
     * @return boolean
     */
    public function contains($haystack, $needle)
    {
        if (!$needle) {
            return false;
        }
        return preg_match('/\b(' . $needle . ')\b/i', strip_tags($haystack)) > 0;
    }

    /**
     * getCrumbs - returns the crumbs
     *
     * @return ArrayList
     */
    public function getCrumbs()
    {
        $crumbs = [];
        foreach (explode('/', $this->link) as $value) {
            if ($value != '') {
                $crumbs[] = [
                    'Crumb' => $value
                ];
            }
        }
        return ArrayList::create($crumbs);
    }
}
