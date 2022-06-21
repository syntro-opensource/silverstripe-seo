<?php
namespace Syntro\SEO\Extensions;

use SilverStripe\Control\Director;
use SilverStripe\Forms\TextField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Assets\Image;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\i18n\i18n;

/**
 * The SEO extension adds a n SEO analysis Tab
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class SiteConfigExtension extends DataExtension
{
    /**
     * Database fields
     * @config
     * @var array
     */
    private static $db = [
        'SEOSocialFacebook' => 'Varchar',
        'SEOSocialInstagram' => 'Varchar',
        'SEOSocialLinkedin' => 'Varchar',
        'SEOSocialTwitter' => 'Varchar',
    ];

    /**
     * Has_one relationship
     * @config
     * @var array
     */
    private static $has_one = [
        'CompanyLogo' => Image::class,
    ];

    /**
     * Relationship version ownership
     * @config
     * @var array
     */
    private static $owns = [
        'CompanyLogo'
    ];

    /**
     * Update Fields
     *
     * @param FieldList $fields the original fields
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {
        $owner = $this->owner;
        $fields->addFieldToTab(
            'Root.Main',
            $logoField = UploadField::create(
                'CompanyLogo',
                _t(__CLASS__ . '.LOGOTITLE', 'Logo')
            ),
            'Title'
        );
        $logoField
            ->setFolderName('Logos')
            ->setAllowedMaxFileNumber(1)
            ->setRightTitle(_t(__CLASS__ . '.LOGODESC', 'This should be your company logo and is used for search results'));

        $fields->addFieldToTab(
            'Root.Main',
            ToggleCompositeField::create('seoSocials', _t(__CLASS__ . '.SOMEPAGESTITLE', 'Social Media Pages'), [
                $facebookField = TextField::create(
                    'SEOSocialFacebook',
                    _t(__CLASS__ . '.SOMEPAGESFACEBOOKTITLE', 'Your Facebook Page')
                ),
                $instagramField = TextField::create(
                    'SEOSocialInstagram',
                    _t(__CLASS__ . '.SOMEPAGESINSTAGRAMTITLE', 'Your Instagram Page')
                ),
                $linkedinField = TextField::create(
                    'SEOSocialLinkedin',
                    _t(__CLASS__ . '.SOMEPAGESLINKEDINTITLE', 'Your Linkedin Page')
                ),
                $twitterField = TextField::create(
                    'SEOSocialTwitter',
                    _t(__CLASS__ . '.SOMEPAGESTWITTERTITLE', 'Your Twitter Page')
                ),
            ])
        );
        $facebookField->setAttribute('placeholder', 'https://www.facebook.com/<company-name>/');
        $instagramField->setAttribute('placeholder', 'https://www.instagram.com/<company-name>/');
        $linkedinField->setAttribute('placeholder', 'https://www.linkedin.com/company/<company-name>/');
        $twitterField->setAttribute('placeholder', 'https://www.twitter.com/<company-name>/');
        return $fields;
    }

    /**
     * getOrganisationSchema - generates the organization part
     *
     * @return array
     */
    public function getOrganisationSchema()
    {
        $owner = $this->getOwner();
        $baseURL = Director::absoluteBaseURL();
        $sameAsArray = [];
        if ($owner->SEOSocialFacebook) {
            $sameAsArray[] = $owner->SEOSocialFacebook;
        }
        if ($owner->SEOSocialInstagram) {
            $sameAsArray[] = $owner->SEOSocialInstagram;
        }
        if ($owner->SEOSocialLinkedin) {
            $sameAsArray[] = $owner->SEOSocialLinkedin;
        }
        if ($owner->SEOSocialTwitter) {
            $sameAsArray[] = $owner->SEOSocialTwitter;
        }

        $orgSchema =  [
            "@type" => "Organization",
            "@id" => "$baseURL#organization",
            'name' => $owner->Title,
            'url' => $baseURL,
        ];
        if (count($sameAsArray)) {
            $orgSchema['sameAs'] = $sameAsArray;
        }
        if ($owner->CompanyLogoID != 0) {
            $orgSchema['logo'] = [
                "@type" => "ImageObject",
                "inLanguage" => i18n::get_locale(),
                "@id" => "$baseURL#/schema/logo/image/",
                "url" => $owner->CompanyLogo->getURL(),
                "contentUrl" => $owner->CompanyLogo->getURL(),
                "width" => $owner->CompanyLogo->getWidth(),
                "height" => $owner->CompanyLogo->getHeight(),
                "caption" => $owner->Title,
            ];
            $orgSchema['image'] = [
                '@id' => "$baseURL#/schema/logo/image/"
            ];
        }
        return $orgSchema;
    }

    /**
     * getWebsiteSchema - get the Website part of the schema
     *
     * @return array
     */
    public function getWebsiteSchema()
    {
        $baseURL = Director::absoluteBaseURL();
        return [
            "@type" => "WebSite",
            "@id" => "$baseURL#website",
            'url' => $baseURL,
            'name' => $this->getOwner()->Title,
            'description' => $this->getOwner()->Tagline,
            'publisher' => [
                '@id' => "$baseURL#organization"
            ],
            "inLanguage" => i18n::get_locale(),
        ];
    }
}
