<?php

namespace Syntro\Seo\Tests\Preview;

use SilverStripe\Dev\SapphireTest;
use Syntro\Seo\Preview\SERPPreview;
use SilverStripe\Control\Director;
use SilverStripe\ORM\ArrayList;
use Page;

/**
 * Test the SERPPreview class /**
  * @author Ronald Studer <ronald@syntro.ch>
  */
class SERPPreviewTest extends SapphireTest
{

  /**
   * testMetadescription
   *
   * @return void
   */
    public function testMetaDescription()
    {
        $page = Page::create();
        $page->MetaDescription = 'test';
        $serpPreview = SERPPreview::create($page);

        $this->assertEquals(
            '<strong></strong>' . $page->MetaDescription . '<strong></strong>',
            $serpPreview->MetaDescription()
        );
    }

  /**
   * testTitle
   *
   * @return void
   */
    public function testTitle()
    {
        $page = Page::create();
        $serpPreview = SERPPreview::create($page);
        $serpPreview->getDom()->find('title')->firstChild()->setText('test');

        $this->assertEquals(
            '<strong></strong>test<strong></strong>',
            $serpPreview->Title()
        );
    }

  /**
   * testBaseURL
   *
   * @return void
   */
    public function testBaseURL()
    {
        $page = Page::create();
        $serpPreview = SERPPreview::create($page);

        $this->assertEquals(
            Director::host(),
            $serpPreview->BaseURL()
        );
    }

  /**
   * testCrumbs
   *
   * @return void
   */
    public function testCrumbs()
    {
        $page = Page::create();
        $serpPreview = SERPPreview::create($page);

        $this->assertInstanceOf(ArrayList::class, $serpPreview->Crumbs());
    }

  /**
   * testRightTitle
   *
   * @return void
   */
    public function testRightTitle()
    {
        $page = Page::create();
        $serpPreview = SERPPreview::create($page);

        $this->assertEquals(
            'SERP (Search Engine Result Page) snippets show how a user sees this page on Google',
            $serpPreview->RightTitle()
        );
    }
}
