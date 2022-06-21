<?php

namespace Syntro\SEO\Tests;

use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\CMS\Model\SiteTree;
use Syntro\SEO\DOM;

/**
 * Tests that a page has a correct schema graph applied
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class SchemaGraphTest extends FunctionalTest
{
    /**
     * Defines the fixture file to use for this test class
     * @var string
     */
    protected static $fixture_file = './SchemaGraphFixture.yml';

    public function testSchemaScriptTagIsRendered()
    {
        $page = $this->objFromFixture(\Page::class, 'child');
        $page->copyVersionToStage('Stage', 'Live');

        $response = $this->get('contact-us/child/');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertcontains('<script type="application/ld+json" class="ss-schema-graph">', $response->getBody());
    }

    /**
     * testOrganizationSchemaName
     *
     * @return void
     */
    public function testOrganizationSchemaName()
    {
        $siteconfig = SiteConfig::current_site_config();
        $siteconfig->Title = 'TestTitle';
        $siteconfig->write();
        $schema = $siteconfig->getOrganisationSchema();
        $this->assertEquals('Organization', $schema['@type']);
        $this->assertArrayHasKey('@id', $schema);
        $this->assertEquals('TestTitle', $schema['name']);
        $this->assertArrayHasKey('url', $schema);
    }

    /**
     * testOrganizationSchemaSameAs
     *
     * @return void
     */
    public function testOrganizationSchemaSameAs()
    {
        $siteconfig = SiteConfig::current_site_config();
        $schema = $siteconfig->getOrganisationSchema();
        $this->assertArrayNotHasKey('sameAs', $schema);

        $siteconfig->SEOSocialFacebook = 'SEOSocialFacebook';
        $siteconfig->SEOSocialInstagram = 'SEOSocialInstagram';
        $siteconfig->SEOSocialLinkedin = 'SEOSocialLinkedin';
        $siteconfig->SEOSocialTwitter = 'SEOSocialTwitter';
        $siteconfig->write();
        $schema = $siteconfig->getOrganisationSchema();
        $this->assertArrayHasKey('sameAs', $schema);
        $this->assertContains('SEOSocialFacebook', $schema['sameAs']);
        $this->assertContains('SEOSocialInstagram', $schema['sameAs']);
        $this->assertContains('SEOSocialLinkedin', $schema['sameAs']);
        $this->assertContains('SEOSocialTwitter', $schema['sameAs']);
    }

    // TODO: write a test that checks logo

    /**
     * testWebSiteSchema
     *
     * @return void
     */
    public function testWebSiteSchema()
    {
        $siteconfig = SiteConfig::current_site_config();
        $siteconfig->Title = 'TestTitle';
        $siteconfig->Tagline = 'TestTagline';
        $siteconfig->write();
        $schema = $siteconfig->getWebsiteSchema();

        $this->assertEquals('WebSite', $schema['@type']);
        $this->assertArrayHasKey('@id', $schema);
        $this->assertArrayHasKey('url', $schema);
        $this->assertEquals('TestTitle', $schema['name']);
        $this->assertEquals('TestTagline', $schema['description']);
        $this->assertArrayHasKey('publisher', $schema);
        $this->assertArrayHasKey('inLanguage', $schema);
    }

    /**
     * testWebPageSchema
     *
     * @return void
     */
    public function testWebPageSchema()
    {
        $page = $this->objFromFixture(\Page::class, 'home');

        $schema = $page->getWebPageSchema();
        $this->assertEquals('WebPage', $schema['@type']);
        $this->assertArrayHasKey('@id', $schema);
        $this->assertArrayHasKey('url', $schema);
        $this->assertEquals('I am home', $schema['name']);
        $this->assertArrayHasKey('isPartOf', $schema);
        $this->assertArrayHasKey('datePublished', $schema);
        $this->assertArrayHasKey('dateModified', $schema);
        $this->assertArrayHasKey('breadcrumb', $schema);
        $this->assertArrayHasKey('inLanguage', $schema);

        $this->assertArrayHasKey('potentialAction', $schema);
    }

    /**
     * testBreadcrumbListSchemaForHomepage
     *
     * @return void
     */
    public function testBreadcrumbListSchemaForHomepage()
    {
        $page = $this->objFromFixture(\Page::class, 'home');
        $schema = $page->getBreadcrumbListSchema($page);

        $this->assertEquals('BreadcrumbList', $schema['@type']);
        $this->assertArrayHasKey('@id', $schema);
        $this->assertArrayHasKey('itemListElement', $schema);
        $bcList = $schema['itemListElement'];
        $this->assertEquals(1, count($bcList));

        $this->assertEquals('ListItem', $bcList[0]['@type']);
        $this->assertEquals('I am home', $bcList[0]['name']);
        $this->assertEquals(1, $bcList[0]['position']);
    }

    /**
     * testBreadcrumbListSchemaForTopLevelPage
     *
     * @return void
     */
    public function testBreadcrumbListSchemaForTopLevelPage()
    {
        $page = $this->objFromFixture(\Page::class, 'aboutus');
        $schema = $page->getBreadcrumbListSchema($page);

        $this->assertEquals('BreadcrumbList', $schema['@type']);
        $this->assertArrayHasKey('@id', $schema);
        $this->assertArrayHasKey('itemListElement', $schema);
        $bcList = $schema['itemListElement'];
        $this->assertEquals(2, count($bcList));

        $this->assertEquals('ListItem', $bcList[0]['@type']);
        $this->assertEquals('I am home', $bcList[0]['name']);
        $this->assertEquals(1, $bcList[0]['position']);

        $this->assertEquals('ListItem', $bcList[1]['@type']);
        $this->assertEquals('I am about-us', $bcList[1]['name']);
        $this->assertEquals(2, $bcList[1]['position']);
    }

    /**
     * testBreadcrumbListSchemaForSubpage
     *
     * @return void
     */
    public function testBreadcrumbListSchemaForSubpage()
    {
        $page = $this->objFromFixture(\Page::class, 'child');
        $schema = $page->getBreadcrumbListSchema($page);

        $this->assertEquals('BreadcrumbList', $schema['@type']);
        $this->assertArrayHasKey('@id', $schema);
        $this->assertArrayHasKey('itemListElement', $schema);
        $bcList = $schema['itemListElement'];
        $this->assertEquals(3, count($bcList));

        $this->assertEquals('ListItem', $bcList[0]['@type']);
        $this->assertEquals('I am home', $bcList[0]['name']);
        $this->assertEquals(1, $bcList[0]['position']);

        $this->assertEquals('ListItem', $bcList[1]['@type']);
        $this->assertEquals('I am about-us', $bcList[1]['name']);
        $this->assertEquals(2, $bcList[1]['position']);

        $this->assertEquals('ListItem', $bcList[2]['@type']);
        $this->assertEquals('I am child', $bcList[2]['name']);
        $this->assertEquals(3, $bcList[2]['position']);
    }
}
