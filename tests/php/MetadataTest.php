<?php

namespace Syntro\Seo\Tests;

use SilverStripe\Dev\SapphireTest;
use Syntro\Seo\Metadata;
use Syntro\Seo\Tags\Tag;
use Syntro\Seo\Tags\OGTag;
use Syntro\Seo\Tags\TwitterTag;
use Syntro\Seo\Tags\OGImageTag;
use Page;

/**
 * test Metadata
 *
 * @author Ronald Studer
 */
class MetadataTest extends SapphireTest
{

  /**
   * testSetters
   *
   * @return void
   */
    public function testSetters()
    {
        $page = Page::create();
        $newPage = Page::create();
        $metadata = Metadata::create($page);

        $this->assertEquals(
            $metadata,
            $metadata->setSource($newPage)
        );
    }

  /**
   * testGetters
   *
   * @return void
   */
    public function testGetters()
    {
        $page = Page::create();
        $metadata = Metadata::create($page);

        $this->assertEquals(
            $page,
            $metadata->getSource()
        );
    }

  /**
   * testPushTag
   *
   * @return void
   */
    public function testPushTag()
    {
        $page = Page::create();
        $tag = Tag::create('Test', ['href' => 'test'], 'a');
        $metadata = Metadata::create($page);

        $this->assertEquals(
            $metadata,
            $metadata->pushTag($tag)
        );
    }


  /**
   * testRemoveTag
   *
   * @return void
   */
    public function testRemoveTag()
    {
        $page = Page::create();
        $tag = Tag::create('Test', ['href' => 'test'], 'a');
        $metadata = Metadata::create($page);
        $metadata->pushTag($tag);

        $this->assertEquals(
            $metadata,
            $metadata->removeTag($tag->getName())
        );
    }


  /**
   * testGetTagsForRendering
   *
   * @return void
   */
    public function testGetTagsForRendering()
    {
        $page = Page::create();
        $metadata = Metadata::create($page);
        $array = $metadata->getTagsForRender();

        $this->assertEquals(
            $array,
            $metadata->getTagsForRender()
        );
    }
}
