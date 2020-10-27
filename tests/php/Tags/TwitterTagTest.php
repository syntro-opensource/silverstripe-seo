<?php

namespace Syntro\Seo\Tests\Tags;

use SilverStripe\Dev\SapphireTest;
use Syntro\Seo\Tags\TwitterTag;

/**
 * Test the TwitterTag class /**
  * @author Ronald Studer <ronald@syntro.ch>
  */
class TwitterTagTest extends SapphireTest
{
  /**
   * testRendering
   *
   * @return void
   */
    public function testRendering()
    {
        $twitterTag = TwitterTag::create('testName', 'testProperty', 'testContent');

        $this->assertEquals(
            [
            'tag' => 'meta',
            'attributes' => [
            'name' => 'testProperty',
            'content' => 'testContent'
            ]
            ],
            $twitterTag->forRender()
        );
    }

  /**
   * testContentlessRendering
   *
   * @return void
   */
    public function testContentlessRendering()
    {
        $twitterTag = TwitterTag::create('testName', 'testProperty', '');
        $this->assertEquals(
            [],
            $twitterTag->forRender()
        );
    }
}
