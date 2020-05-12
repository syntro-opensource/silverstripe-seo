<?php
namespace Syntro\SEOMeta\Preview;

use SilverStripe\View\ViewableData;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Control\Director;
use SilverStripe\ORM\FieldType\DBText;

use Syntro\SEOMeta\Preview\Preview;

/**
 * Field to preview the Page as a google result
 */
class SERPPreview extends Preview
{

    /**
     * @var int
     */
    protected $google_max_title_length = 70;

    /**
     * @var int
     */
    protected $google_max_descr_length = 160;

    /**
     * Ensures that the methods are wrapped in the correct type and
     * values are safely escaped while rendering in the template.
     * @var array
     */
    private static $casting = [
        'Title' => 'HTMLText',
        'MetaDescription' => 'HTMLText'
    ];

    public function MetaDescription()
    {
        $description = $this->getDom()->find('meta[name=description]',0);
        $description = !is_null($description)
            ? $description->getAttributes()['content']
            : null;
        $container = DBText::create('Title')
            ->setValue($description)
            ->LimitCharacters($this->google_max_descr_length,'...');
        return $this->highlight($container, $this->getFocus());
    }

    public function Title()
    {
        $Title = $this->getDom()->find('title',0);
        $Title = $Title
            ? $Title->text()
            : '';
        $container = DBText::create('Title')
            ->setValue($Title)
            ->LimitCharacters($this->google_max_title_length,'...');
        return $this->highlight($container, $this->getFocus());
    }


    public function BaseURL()
    {
        return Director::host();
    }

    public function Crumbs()
    {
        $string = $this->getPage()->Link();
        $crumbs = [];
        foreach (explode('/',$string) as $value) {
            if ($value != '') {
                $crumbs[] = [
                    'Crumb' => $value
                ];
            }
        }
        return ArrayList::create($crumbs);
    }

    public function RightTitle()
    {
        return _t(__CLASS__ . '.Description', 'This is how this Page is presented by Google');
    }
}
