<?php
namespace Syntro\Seo\Preview;

use SilverStripe\View\ViewableData;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Control\Director;
use SilverStripe\ORM\FieldType\DBText;

use Syntro\Seo\Preview\Preview;
use Syntro\Seo\Seo;

/**
 * Field to preview the Page as a google result
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class SERPPreview extends Preview
{

    /**
     * Ensures that the methods are wrapped in the correct type and
     * values are safely escaped while rendering in the template.
     * @var array
     */
    private static $casting = [
        'Title' => 'HTMLText',
        'MetaDescription' => 'HTMLText'
    ];


    /**
     * getFocus - returns the focus keyword
     *
     * @return string
     */
    public function getFocus()
    {
        return $this->getPage()->dbObject('FocusKeyword');
    }

    /**
     * MetaDescription - returns the meta description
     *
     * @return string
     */
    public function MetaDescription()
    {
        $description = $this->getDom()->find('meta[name=description]', 0);
        $description = !is_null($description)
            ? $description->getAttributes()['content']
            : null;
        $container = DBText::create('Title')
            ->setValue($description)
            ->LimitCharacters(Seo::GOOGLE_MAX_DESCRIPTION_LENGTH, '...');
        return $this->highlight($container, $this->getFocus());
    }


    /**
     * Title - tfinds the metatitle
     *
     * @return string
     */
    public function Title()
    {
        $Title = $this->getDom()->find('title', 0);
        $Title = $Title
            ? $Title->text()
            : '';
        $container = DBText::create('Title')
            ->setValue($Title)
            ->LimitCharacters(Seo::GOOGLE_MAX_TITLE_LENGTH, '...');
        return $this->highlight($container, $this->getFocus());
    }


    /**
     * BaseURL
     *
     * @return string
     */
    public function BaseURL()
    {
        return Director::host();
    }

    /**
     * Crumbs - returns the crumbs
     *
     * @return ArrayList
     */
    public function Crumbs()
    {
        $string = $this->getPage()->Link();
        $crumbs = [];
        foreach (explode('/', $string) as $value) {
            if ($value != '') {
                $crumbs[] = [
                    'Crumb' => $value
                ];
            }
        }
        return ArrayList::create($crumbs);
    }

    /**
     * RightTitle
     *
     * @return string
     */
    public function RightTitle()
    {
        return _t(__CLASS__ . '.Description', 'This is how this Page is presented by Google');
    }
}
