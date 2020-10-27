<?php

namespace Syntro\Seo\Tests\Reports;

use SilverStripe\Dev\SapphireTest;
use Syntro\Seo\Reports\MissingMetaDescriptionReport;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\CMS\Model\RedirectorPage;
use SilverStripe\ErrorPage\ErrorPage;

/**
 * Test the MissingMetaDescriptionReport class /**
  * @author Ronald Studer <ronald@syntro.ch>
  */
class MissingMetaDescriptionReportTest extends SapphireTest
{

  /**
   * testTitle
   *
   * @return void
   */
    public function testTitle()
    {
        $missingMetaDescriptionReport = MissingMetaDescriptionReport::create();

        $this->assertEquals(
            'Pages with missing meta description',
            $missingMetaDescriptionReport->title()
        );
    }

  /**
   * testDescription
   *
   * @return void
   */
    public function testDescription()
    {
        $missingMetaDescriptionReport = MissingMetaDescriptionReport::create();

        $this->assertEquals(
            '<div class="alert alert-info">It is useful to set a meta description for each page. This helps with indexing and displaying the specific page by search engines.</div>',
            $missingMetaDescriptionReport->description()
        );
    }

  /**
   * testSourceRecords
   *
   * @return void
   */
    public function testSourceRecords()
    {
        $missingMetaDescriptionReport = MissingMetaDescriptionReport::create();

        $this->assertEquals(
            SiteTree::get()->filter([
            'MetaDescription' => null,
            'ClassName:not' => ErrorPage::class
            ])->filter([
            'ClassName:not' => RedirectorPage::class,
            ]),
            $missingMetaDescriptionReport->sourceRecords()
        );
    }

  /**
   * testColumns
   *
   * @return void
   */
    public function testColumns()
    {
        $missingMetaDescriptionReport = MissingMetaDescriptionReport::create();

        $this->assertEquals(
            ["Title" => [
            "title" => "Title",
            "link" => true,
            ]],
            $missingMetaDescriptionReport->columns()
        );
    }
}
