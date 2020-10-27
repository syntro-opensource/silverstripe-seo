<?php

namespace Syntro\Seo\Tests\Tags;

use SilverStripe\Dev\SapphireTest;
use Syntro\Seo\Tags\OGTag;

/**
 * Test the OGTag /**
  * @author Ronald Studer <ronald@syntro.ch>
  */
class OGTagTest extends SapphireTest
{

  /**
   * testRendering
   *
   * @return void
   */
    public function testRendering()
    {
        $ogTag = OGTag::create('testName', 'testProperty', 'testContent');

        $this->assertEquals(
            [
            'tag' => 'meta',
            'attributes' => [
            'property' => 'testProperty',
            'content' => 'testContent'
            ]
            ],
            $ogTag->forRender()
        );
    }

  /**
   * testContentlessRendering
   *
   * @return void
   */
    public function testContentlessRendering()
    {
        $ogTag = OGTag::create('testName', 'testProperty', '');

        $this->assertEquals(
            [],
            $ogTag->forRender()
        );
    }
}
