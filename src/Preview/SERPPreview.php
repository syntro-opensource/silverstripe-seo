<?php
namespace Syntro\SEOMeta\Preview;

use SilverStripe\View\ViewableData;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Control\Director;

use Syntro\SEOMeta\Preview\Preview;

/**
 * Field to preview the Page as a google result
 */
class SERPPreview extends Preview
{
    public function MetaDescription()
    {
        $description = $this->getDom()->find('meta[name=description]',0);
        return !is_null($description) ? $description->getAttributes()['content'] : null;
    }

    public function Title()
    {
        $Title = $this->getDom()->find('title',0);
        return $Title ? $Title->text() : '';
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
