<?php
namespace Syntro\Seo;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use Syntro\Seo\Tags\Tag;
use Syntro\Seo\Tags\OGTag;
use Syntro\Seo\Tags\TwitterTag;
use Syntro\Seo\Tags\OGImageTag;

/**
 * Handles meta tag generation and modification
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class Metadata
{
    use Injectable, Configurable;

    /**
     * @config
     * @var array
     */
    private static $available_og_types = [
        'website',
        'article'
    ];

    /**
     * @config
     * @var array
     */
    private static $available_twitter_types = [
        'summary',
        'summary_large_image',
        'app',
        'player'
    ];

    /**
     * Object we are working with
     * @var DataObject|SiteTree
     */
    private $source;

    /**
     * The current tags
     */
    private $tags;

    /**
     * __construct
     *
     * @param  DataObject|Page $source the page we are working with
     * @return void
     */
    function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * setObject - sets the source from which
     *
     * @param  DataObject|Page $value description
     * @return Metadata
     */
    public function setSource($value)
    {
        $this->source = $value;
        return $this;
    }

    /**
     * getSource - return the current meta source
     *
     * @return DataObject|Page
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * setTags
     *
     * @param  array $tags the array containing the tags
     * @return Metadata
     */
    private function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * getTags - returns the current tag list. If this list is not yet populated,
     * it is populated here
     *
     * @return array
     */
    private function getTags()
    {
        if (!isset($this->tags) || is_null($this->tags)) {
            $this->tags = $this->populateDefaultTags();
        }
        return $this->tags;
    }

    /**
     * populateDefaultTags - populate the default tags
     *
     * @return Metadata
     */
    private function populateDefaultTags()
    {
        $source = $this->getSource();
        $tags = [
            'og:type' => OGTag::create('og:type', 'og:type', $source->OGTypeForTemplate()),
            'og:name' => OGTag::create('og:name', 'og:name', $source->OGNameForTemplate()),
            'og:title' => OGTag::create('og:title', 'og:title', $source->OGTitleForTemplate()),
            'og:url' => OGTag::create('og:url', 'og:url', $source->AbsoluteLink()),
            'og:description' => OGTag::create('og:description', 'og:description', $source->OGDescriptionForTemplate()),
            'og:image' => OGImageTag::create('og:image', $source->OGImageForTemplate()),
            'twitter:card' => TwitterTag::create('twitter:card', 'twitter:card', $source->TwitterCardForTemplate()),
            'twitter:site' => TwitterTag::create('twitter:site', 'twitter:site', $source->TwitterSiteForTemplate()),
            'article:published_time' => Tag::create('article:published_time', [
                'property' => 'article:published_time',
                'content' => $source->Created,
            ], 'meta'),
            'article:modified_time' => Tag::create('article:modified_time', [
                'property' => 'article:modified_time',
                'content' => $source->LastEdited,
            ], 'meta'),
        ];


        return $tags;
    }


    /**
     * pushTag - pushes a Tag to the end of the list of created tags. this will
     * overwrite any tag with the same name
     *
     * @param  Tag $tag the tag to be pushed
     * @return Metadata
     */
    public function pushTag(Tag $tag)
    {
        $tags = $this->getTags();

        $currentName = $tag->getName();
        $tags[$currentName] = $tag;

        $this->setTags($tags);
        return $this;
    }

    /**
     * removeTag - remove a tag from the tag list by name
     *
     * @param  string $name the name of the Tag to be removed
     * @return Metadata
     */
    public function removeTag($name)
    {
        $tags = $this->getTags();
        unset($tags[$name]);
        $this->setTags($tags);
        return $this;
    }

    /**
     * getTagsForRender - returns an array containing the Tags in the form required
     * by the CMS
     *
     * @return array
     */
    public function getTagsForRender()
    {
        $tags = $this->getTags();
        $tagsForRender = [];

        foreach ($tags as $tag) {
            if ($tag instanceof OGImageTag) {
                $tagsForRender = array_merge($tagsForRender, $tag->forRender());
            } else {
                $tagsForRender[] = $tag->forRender();
            }
        }
        return $tagsForRender;
    }
}
