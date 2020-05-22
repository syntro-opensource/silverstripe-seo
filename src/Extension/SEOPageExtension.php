<?php
// namespace Syntro\Seo\Extension;
//
// use SilverStripe\Forms\DropdownField;
// use SilverStripe\AssetAdmin\Forms\UploadField;
// use SilverStripe\Forms\TextField;
// use SilverStripe\Forms\FieldList;
// use SilverStripe\ORM\DataExtension;
// use SilverStripe\Forms\TextareaField;
// use SilverStripe\Forms\ToggleCompositeField;
// use SilverStripe\Forms\LiteralField;
// use SilverStripe\Assets\Image;
// use Silverstripe\SiteConfig\SiteConfig;
// use SilverStripe\Control\Controller;
// use SilverStripe\VersionedAdmin\Controllers\HistoryViewerController;
//
// use Syntro\Seo\Seo;
// use Syntro\Seo\Forms\SeoAnalysisField;
// use Syntro\Seo\Preview\SERPPreview;
// use Syntro\Seo\Generator\OGMetaGenerator;
// use Syntro\Seo\Generator\TwitterMetaGenerator;
//
// class SEOPageExtension extends DataExtension
// {
//
//     /**
//      * Database fields
//      * @var array
//      */
//     private static $db = [
//         'FocusKeyword' => 'Varchar',
//         'OGType' => 'Varchar(20)',
//         'OGTitle' => 'Varchar',
//         'OGDescription' => 'Varchar',
//         'TwitterType' => 'Varchar(20)',
//         'TwitterCreator' => 'Varchar',
//     ];
//
//     /**
//      * Has_one relationship
//      * @var array
//      */
//     private static $has_one = [
//         'OGImage' => Image::class
//     ];
//
//     /**
//      * Relationship version ownership
//      * @var array
//      */
//     private static $owns = [
//         'OGImage'
//     ];
//
//     /**
//      * Add default values to database
//      * @var array
//      */
//     private static $defaults = [
//         'OGType' => 'website',
//         'TwitterType' => 'summary'
//     ];
//
//
//     /**
//      * Update Fields
//      * @return FieldList
//      */
//     public function updateCMSFields(FieldList $fields)
//     {
//         if (Controller::curr() instanceof HistoryViewerController) { // avoid breaking the history comparison UI
//             return;
//         }
//         $owner = $this->owner;
//
//         // Add the SEO Health fields
//         $fields->addFieldToTab(
//             'Root.SEO',
//             $healthFocusKeywordField = TextField::create('FocusKeyword', 'Focus Keyword')
//         );
//         $fields->addFieldToTab(
//             'Root.SEO',
//             $SERPPreviewField = LiteralField::create('SERPPreview', SERPPreview::create($owner))
//         );
//         $fields->addFieldToTab(
//             'Root.SEO',
//             $SeoAnalysisField = SeoAnalysisField::create('SEOAnalysis', 'SEO Analysis', $owner)
//         );
//         $healthFocusKeywordField
//             ->setRightTitle(_t(__CLASS__ . '.KeyWordDesc', 'Set a Keyword which you want to focus this page around'));
//
//
//         // Move meta field to the new SEO-Tab
//         $metaDescriptionField = $fields->dataFieldByName('MetaDescription');
//         $metaExtraField = $fields->dataFieldByName('ExtraMeta');
//         if (!is_null($metaDescriptionField) && !is_null($metaExtraField)) {
//             $fields->removeByName([
//                 'MetaDescription',
//                 'ExtraMeta',
//                 'Metadata'
//             ]);
//             $fields->addFieldToTab(
//                 'Root.SEO',
//                 ToggleCompositeField::create(
//                     'Metadata',
//                     _t(__CLASS__ . '.MetadataToggle', 'Metadata'),
//                     [
//                         $metaDescriptionField,
//                         $metaExtraField
//                     ]
//                 )->setHeadingLevel(4)
//             );
//             $metaDescriptionField->setTargetLength(125);
//
//
//             // Add Opengraph Meta
//             $OGTypes = [];
//             foreach (OGMetaGenerator::config()->available_types as $value) {
//                 $OGTypes[$value] = _t(OGMetaGenerator::class . '.' . $value, $value);
//             }
//             $fields->addFieldToTab(
//                 'Root.SEO',
//                 ToggleCompositeField::create(
//                     'OpenGraph',
//                     _t(__CLASS__ . '.OpenGraphToggle', 'OpenGraph SEO (Facebook)'),
//                     [
//                         $ogType = DropdownField::create(
//                             'OGType',
//                             'Type',
//                             $OGTypes
//                         ),
//                         $ogTitle = TextField::create('OGTitle', 'Title'),
//                         $ogImage = UploadField::create('OGImage', 'Image'),
//                         $ogDescription = TextareaField::create('OGDescription', 'Description'),
//                     ]
//                 )->setHeadingLevel(4)
//             );
//             $ogTitle->setAttribute('placeholder', $owner->Title)->setTargetLength(50);
//             $ogDescription->setAttribute('placeholder', $owner->MetaDescription);
//
//
//             // Add Twitter Meta
//             $TwitterTypes = [];
//             foreach (TwitterMetaGenerator::config()->available_types as $value) {
//                 $TwitterTypes[$value] = _t(TwitterMetaGenerator::class . '.' . $value, $value);
//             }
//             $fields->addFieldToTab(
//                 'Root.SEO',
//                 ToggleCompositeField::create(
//                     'Twitter',
//                     _t(__CLASS__ . '.OpenGraphToggle', 'Twitter SEO'),
//                     [
//                         $twitterType = DropdownField::create(
//                             'TwitterType',
//                             'Type',
//                             $TwitterTypes
//                         ),
//                         $twitterTitle = TextField::create('TwitterCreator', 'Creator'),
//                     ]
//                 )->setHeadingLevel(4)
//             );
//         }
//         return $fields;
//     }
// }
