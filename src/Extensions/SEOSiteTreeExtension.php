<?php
namespace Syntro\SEO\Extensions;

use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataExtension;
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
    protected $source = null;

    /**
     * getSEOSource - returns the set SEO source
     *
     * @return SiteTree|DataObject
     */
    public function getSEOSource()
    {
        if ($this->source) {
            return $this->source;
        } else {
            $owner = $this->getOwner();
            $this->setSEOSource($owner);
            return $owner;
        }
    }

    /**
     * setSEOSource - set the source
     *
     * @param  SiteTree|DataObject $source the source
     * @return void
     */
    public function setSEOSource($source)
    {
        $this->source = $source;
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
        $source = $this->getSEOSource();
        if (!$source->hasExtension(SEOExtension::class)) {
            return $tags;
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

        return $tags;
    }
}
