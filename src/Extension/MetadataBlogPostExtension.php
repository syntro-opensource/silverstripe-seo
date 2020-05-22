<?php
namespace Syntro\Seo\Extension;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Control\Controller;


/**
 * This applies the necessary functions to retrieve the correct metadata from
 * a Blog Post
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class MetadataBlogPostExtension extends DataExtension
{
    /**
     * Ensures that the methods are wrapped in the correct type and
     * values are safely escaped while rendering in the template.
     * @var array
     */
    private static $casting = [
        'OGDescriptionForTemplate' => 'Text'
    ];

    /**
     * OGTypeForTemplate - fetches the type of this object
     *
     * @return string|null
     */
    public function OGTypeForTemplate()
    {
        return 'article';
    }

    /**
     * OGDescriptionForTemplate - returns the description.
     * falls back to the meta description
     *
     * @return string
     */
    public function OGDescriptionForTemplate()
    {
        $owner = $this->getOwner();
        if ($owner->Summary) {
            return $owner->Summary;
        }
        return $owner->Excerpt();
    }

    /**
     * OGImageForTemplate - returns an Image. Falls back to a global default
     * set in the siteconfig
     *
     * @return Image|null
     */
    public function OGImageForTemplate()
    {
        $owner = $this->getOwner();
        return $owner->FeaturedImage;
    }
}
