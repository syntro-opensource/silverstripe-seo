<?php

namespace Syntro\Seo\Tests\Tags;

use SilverStripe\Dev\SapphireTest;
use Syntro\Seo\Tags\Tag;

/**
 * Test the Tag class
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class TagTest extends SapphireTest
{

    /**
     * testGetters
     *
     * @return void
     */
    public function testGetters()
    {
        $tag = Tag::create('Test', ['href' => 'test'], 'a');

        $this->assertEquals('Test', $tag->getName());
        $this->assertEquals(['href' => 'test'], $tag->getData());
        $this->assertEquals('a', $tag->getTag());
    }

    /**
     * testRender
     *
     * @return void
     */
    public function testRender()
    {
        $tag = Tag::create('Test', ['href' => 'test'], 'a');
        $this->assertEquals(
            [
                'tag' => 'a',
                'attributes' => ['href' => 'test']
            ],
            $tag->forRender()
        );
    }
}
