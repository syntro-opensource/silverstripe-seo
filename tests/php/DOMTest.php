<?php

namespace Syntro\SEO\Tests;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\Dev\FunctionalTest;
use Syntro\SEO\DOM;
use SilverStripe\Versioned\Versioned;

/**
 * Tests the DOM class to correctly produce a dom from a page
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class DOMTest extends SapphireTest
{
    /**
     * Defines the fixture file to use for this test class
     * @var string
     */
    protected static $fixture_file = './DOMFixture.yml';

    /**
     * @var boolean
     */
    protected static $use_draft_site = true;

    /**
     * set up
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        Versioned::set_stage(Versioned::DRAFT);
    }

    /**
     * testDomGetter
     *
     * @return void
     */
    public function testRetrievesDomFromPage()
    {
        $dom = DOM::getDom('/test');
        $this->assertTrue(!!strstr($dom->find('body')->text(true), 'This is the nav'));
        $this->assertTrue(!!strstr($dom->find('body')->text(true), 'This is the header'));
        $this->assertTrue(!!strstr($dom->find('body')->text(true), 'This is a section with content'));
        $this->assertTrue(!!strstr($dom->find('body')->text(true), 'This is the footer'));
    }


    /**
     * testDomGetter
     *
     * @return void
     */
    public function testRetrievesStrippedDomFromPage()
    {
        $dom = DOM::getStrippedDom('/test');

        // $dom->find('header')->text(true);
        $this->assertFalse(!!strstr($dom->find('body')->text(true), 'This is the nav'));
        $this->assertFalse(!!strstr($dom->find('body')->text(true), 'This is the header'));
        $this->assertFalse(!!strstr($dom->find('body')->text(true), 'This is the footer'));
        $this->assertTrue(!!strstr($dom->find('body')->text(true), 'This is a section with content'));
    }
}
