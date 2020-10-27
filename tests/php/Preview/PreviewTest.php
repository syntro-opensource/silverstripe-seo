<?php

namespace Syntro\Seo\Tests\Preview;

use SilverStripe\Dev\SapphireTest;
use Syntro\Seo\Preview\Preview;
use Page;
use PHPHtmlParser\Dom;

/**
 * Test the Preview class /**
  * @author Ronald Studer <ronald@syntro.ch>
  */
class PreviewTest extends SapphireTest
{
  /**
   * testGetters
   *
   * @return void
   */
    public function testGetters()
    {
        $page = Page::create();
        $dom = new Dom;
        $preview = Preview::create($page);

        $this->assertEquals(
            $page,
            $preview->getPage()
        );
        $this->assertEquals(
            $dom,
            $preview->setDom($dom)->getDom()
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
        $dom = new Dom;
        $preview = Preview::create($page);

        $this->assertEquals(
            $newPage,
            $preview->setPage($newPage)->getPage()
        );
        $this->assertEquals(
            $dom,
            $preview->setDom($dom)->getDom()
        );
    }

  /**
   * testHighlight
   *
   * @return void
   */
    public function testHighlight()
    {
        $page = Page::create();
        $preview = Preview::create($page);

        $needle = 'lookingForThisTest';
        $haystack = 'sometext lookingForThisTest sometext';

        $this->assertEquals(
            $needle,
            $preview->highlight($needle, $haystack)
        );
    }
}
