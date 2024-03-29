<?php

namespace Syntro\SEO\Tests;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\CMS\Model\SiteTree;
use Syntro\SEO\DOM;

/**
 * Tests that the extension correctly applies meta tags to the page
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class MetaTest extends FunctionalTest
{
    /**
     * Defines the fixture file to use for this test class
     * @var string
     */
    protected static $fixture_file = './MetaFixture.yml';

    /**
     * testGeneratesRobotTagWhenShown
     *
     * @return void
     */
    public function testGeneratesRobotTagWhenShown()
    {
        $page = $this->objFromFixture(\Page::class, 'inSearch');
        $page->copyVersionToStage('Stage', 'Live');

        $response = $this->get('inSearch/');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('<meta name="robots" content="index, follow, max-snippet:-1" />', $response->getBody());
    }

    /**
     * testGeneratesRobotTagWhenShown
     *
     * @return void
     */
    public function testGeneratesRobotTagWhenNotShown()
    {
        $page = $this->objFromFixture(\Page::class, 'notInSearch');
        $page->copyVersionToStage('Stage', 'Live');

        $response = $this->get('notInSearch/');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('<meta name="robots" content="noindex" />', $response->getBody());
    }
}
