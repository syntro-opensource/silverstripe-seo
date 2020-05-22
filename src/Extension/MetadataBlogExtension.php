<?php
namespace Syntro\Seo\Extension;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Control\Controller;
use Syntro\Seo\Tags\Tag;

/**
 * The SEOBlog extension applies the necessary functionality to the Blog
 * class of the silverstripe/blog module.
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class MetadataBlogExtension extends DataExtension
{


    /**
     * UpdateMetadata
     *
     * @return void
     */
    public function UpdateMetadata()
    {
        $metadata = $this->getOwner()->getMetadata();

        /**
         * TODO: ignore this in composer
         * @var mixed
         */
        $controller = Controller::curr();
        if ($current_profile = $controller->getCurrentProfile()) {
            $metadata->pushTag(Tag::create('og:description', [
                'property' => 'og:description',
                'content' => $current_profile->BlogProfileSummary
            ]));
        }
    }
}
