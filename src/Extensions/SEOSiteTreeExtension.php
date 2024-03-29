<?php
namespace Syntro\SEO\Extensions;

use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Control\Controller;
use SilverStripe\CMS\Model\SiteTree;
use Syntro\SEO\Extensions\SEOExtension;

/**
 * The SEOSiteTreeExtension class adds the ability to render SEO specific
 * meta information to a page header.
 * It is using a datasource for the information which can either
 * be a SiteTree subclass (as the SEOExtension is directly added via module) or
 * a DataObject with the SEOExtension applied.
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class SEOSiteTreeExtension extends DataExtension
{

    /**
     * @var array
     */
    protected $source = [];

    /**
     * getSEOSource - returns the set SEO source
     *
     * @return SiteTree|null
     */
    public function getSEOSource()
    {
        $owner = $this->getOwner();
        if (isset($this->source[$owner->Link()])) {
            return $this->source[$owner->Link()];
        } elseif (Controller::curr()->getAction() == 'index') {
            $owner->setSEOSource($owner);
            return $owner;
        }
        return null;
    }

    /**
     * setSEOSource - set the source
     *
     * @param  SiteTree|DataObject $source the source
     * @return void
     */
    public function setSEOSource($source)
    {
        $owner = $this->getOwner();
        $this->source[$owner->Link()] = $source;
    }

    /**
     * MetaComponents - updates the MetaComponents with all necessary stuff.
     *
     * @param  array $tags the original tags
     * @return array
     */
    public function MetaComponents(&$tags)
    {
        /** @var SiteTree $owner */
        $owner = $this->getOwner();
        $source = $owner->getSEOSource();
        if (!$source || !$source->hasExtension(SEOExtension::class)) {
            return $tags;
        }
        // Add robots snippet
        // We respect the "ShowInSearch" Setting for SiteTree objects, for everything
        // else we assume a free pass
        if ($source->ShowInSearch && $source instanceof SiteTree) {
            $tags['robots'] = [
                'tag' => 'meta',
                'attributes' => [
                    'name' => 'robots',
                    'content' => 'index, follow, max-snippet:-1'
                ],
            ];
        } elseif (!$source->ShowInSearch && $source instanceof SiteTree) {
            $tags['robots'] = [
                'tag' => 'meta',
                'attributes' => [
                    'name' => 'robots',
                    'content' => 'noindex'
                ],
            ];
        } else {
            $tags['robots'] = [
                'tag' => 'meta',
                'attributes' => [
                    'name' => 'robots',
                    'content' => 'index, follow, max-snippet:-1'
                ],
            ];
        }

        // Add a title
        $tags['title'] = [
            'tag' => 'title',
            'content' => $source->getSEOTitle() ?? $owner->getSEOTitle(),
        ];

        if ($source->MetaDescription) {
            $tags['description'] = [
                'attributes' => [
                    'name' => 'description',
                    'content' => $source->MetaDescription,
                ],
            ];
        } elseif ($owner->MetaDescription) {
            $tags['description'] = [
                'attributes' => [
                    'name' => 'description',
                    'content' => $owner->MetaDescription,
                ],
            ];
        }

        $tags['graphld'] = [
            'tag' => 'script',
            'attributes' => [
                'type' => 'application/ld+json',
                'class' => 'ss-schema-graph'
            ],
            'content' => $source->getSchemaGraph($owner)
        ];

        return $tags;
    }
}
