<?php

namespace Syntro\Seo\Tests\Tags;

use SilverStripe\Dev\SapphireTest;
use Syntro\Seo\Tags\OGImageTag;
use SilverStripe\Assets\Image;

/**
 * Test the OGImageTag class /**
  * @author Ronald Studer <ronald@syntro.ch>
  */
class OGImageTagTest extends SapphireTest
{

  /**
   * testGetters
   *
   * @return void
   */
  public function testGetters()
  {
    $image = Image::create();
    $ogImageTag = OGImageTag::create('testName', $image);

    $this->assertEquals(
      $image,
      $ogImageTag->getImage()
    );
  }
  /**
   * testRendering
   *
   * @return void
   */
  public function testRendering()
  {
    $image = Image::create();
    $ogImageTag = OGImageTag::create('testName', $image);

    $this->assertEquals(
      5,
      sizeof($ogImageTag->forRender())
    );
  }
}
