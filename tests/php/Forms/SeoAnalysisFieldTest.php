<?php

namespace Syntro\Seo\Tests\Forms;

use SilverStripe\Dev\SapphireTest;
use Syntro\Seo\Forms\SeoAnalysisField;
use SilverStripe\ORM\ArrayList;
use Page;
use PHPHtmlParser\Dom;

/**
 * Test the SeoAnalysisField class /**
  * @author Ronald Studer <ronald@syntro.ch>
  */
class SeoAnalysisFieldTest extends SapphireTest
{
  /**
   * testGetters
   *
   * @return void
   */
    public function testGetters()
    {
        $page = Page::create();
        $seoAnalysisField = SeoAnalysisField::create('test', $page);

        $this->assertEquals(
            $page,
            $seoAnalysisField->getPage()
        );

        $dom = new Dom;
        $this->assertEquals(
            $dom,
            $seoAnalysisField->setDom($dom)->getDom()
        );
    }

  /**
   * testSetters
   *
   * @return void
   */
    public function testSetters()
    {
        $page = Page::create();
        $newPage = Page::create();
        $seoAnalysisField = SeoAnalysisField::create('test', $page);
        $dom = new Dom;

        $this->assertEquals(
            $newPage,
            $seoAnalysisField->setPage($newPage)->getPage()
        );
        $this->assertEquals(
            $dom,
            $seoAnalysisField->setDom($dom)->getDom()
        );
    }

  /**
   * testRunAnalyses
   *
   * @return void
   */
    public function testRunAnalyses()
    {
        $page = Page::create();
        $seoAnalysisField = SeoAnalysisField::create('test', $page);

        $this->assertInstanceOf(
            ArrayList::class,
            $seoAnalysisField->runAnalyses()
        );
    }
}
