<?php

namespace Syntro\Seo\Tests;

use SilverStripe\Dev\SapphireTest;
use Syntro\Seo\Seo;
use Page;

/**
 * Test for Seo class
 * @author Ronald Studer <ronald@syntro.ch>
 */
class SeoTest extends SapphireTest
{

  /**
   * testGettes
   *
   * @return void
   */
    public function testGettes()
    {
        $page = Page::create();
        $seo = Seo::create($page);

        $this->assertEquals(
            $page,
            $seo->getObject()
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
        $seo = Seo::create($page);
        $newPage = Page::create();

        $seo->setObject($newPage);

        $this->assertEquals(
            $newPage,
            $seo->getObject()
        );
    }
}
