<?php
namespace Syntro\Seo\Reports;

use SilverStripe\Reports\Report;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\CMS\Model\RedirectorPage;
use SilverStripe\ErrorPage\ErrorPage;
use SilverStripe\ORM\DataList;

/**
 * Report showing Pages with missing Metadescription
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class MissingMetaDescriptionReport extends Report
{

    /**
     * title
     *
     * @return string
     */
    public function title()
    {
        return _t(__CLASS__ . '.Title', 'Pages with missing meta description');
    }

    /**
     * description
     *
     * @return string
     */
    public function description()
    {
        return _t(__CLASS__ . '.Description', '<div class="alert alert-info">It is useful to set a meta description for each page. This helps with indexing and displaying the specific page by search engines.</div>');
    }

    /**
     * sourceRecords
     *
     * @return DataList
     */
    public function sourceRecords()
    {
        return SiteTree::get()->filter([
            'MetaDescription' => null,
            'ClassName:not' => ErrorPage::class
        ])->filter([
            'ClassName:not' => RedirectorPage::class,
        ]);
    }

    /**
     * columns
     *
     * @return Array
     */
    public function columns()
    {
        return [
            "Title" => [
                "title" => "Title", // todo: use NestedTitle(2)
                "link" => true,
            ],
        ];
    }
}
