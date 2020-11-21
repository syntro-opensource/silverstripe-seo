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
        $page = Page::singleton();
        $dom = new Dom;
        $dom->loadStr('<html></html>');
        $seoAnalysisField = SeoAnalysisField::create('test', $page);
        $seoAnalysisField->setDom($dom);

        $this->assertEquals(
            $page,
            $seoAnalysisField->getPage()
        );

        $this->assertEquals(
            $dom,
            $seoAnalysisField->getDom()
        );
    }

  /**
   * testSetters
   *
   * @return void
   */
    public function testSetters()
    {
        $page = Page::singleton();
        $newPage = Page::singleton();
        $seoAnalysisField = SeoAnalysisField::create('test', $page);
        $dom = new Dom;
        $dom->loadStr('<html></html>');
        $seoAnalysisField->setDom($dom);

        $this->assertEquals(
            $newPage,
            $seoAnalysisField->setPage($newPage)->getPage()
        );
        $this->assertEquals(
            $dom,
            $seoAnalysisField->getDom()
        );
    }

  /**
   * testRunAnalyses
   *
   * @return void
   */
    public function testRunAnalyses()
    {
        $page = Page::singleton();
        $seoAnalysisField = SeoAnalysisField::create('test', $page);

        $this->assertInstanceOf(
            ArrayList::class,
            $seoAnalysisField->runAnalyses()
        );
    }
}
