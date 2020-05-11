<?php
namespace Syntro\SEOMeta\Generator;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Assets\Image;

/**
 * Responsible for generating the open graph meta information
 */
class TwitterMetaGenerator
{
    use Injectable, Configurable;

    /**
     * @config
     * @var array
     */
    private static $available_types = [
        'summary',
        'summary_large_image',
        'app',
        'player'
    ];

    /**
     * @var string|null
     */
    protected $TwitterType;

    /**
     * @var string|null
     */
    protected $TwitterSite;

    /**
     * @var string|null
     */
    protected $TwitterCreator;


    /**
     * getTwitterType
     * @return string|null
     */
    public function getTwitterType()
    {
            if ($this->TwitterType) {
                return $this->TwitterType;
            }
            return 'summary';
    }

    /**
     * getTwitterSite
     * @return string|null
     */
    public function getTwitterSite()
    {
        return $this->TwitterSite;
    }

    /**
     * getTwitterCreator
     * @return string
     */
    public function getTwitterCreator()
    {
        return $this->TwitterCreator;
    }

    /**
     * process - process the tags and generate
     *
     * @return array  all tags that were able to be processed
     */
    public function process()
    {
        $tags = [];

        // twitter:card
        if ($this->getTwitterType()) {
            $tags['twitter:card'] = [
                'attributes' => [
                    'name' => 'twitter:card',
                    'content' => $this->getTwitterType(),
                ],
            ];
        }

        // twitter:site
        if ($this->getTwitterSite()) {
            $tags['twitter:site'] = [
                'attributes' => [
                    'name' => 'twitter:site',
                    'content' => $this->getTwitterSite(),
                ],
            ];
        }

        // twitter:creator
        if ($this->getTwitterCreator()) {
            $tags['twitter:creator'] = [
                'attributes' => [
                    'name' => 'twitter:creator',
                    'content' => $this->getTwitterCreator(),
                ],
            ];
        }


        return $tags;

    }

    /**
     * setTwitterType
     *
     * @param  $value the value
     * @return TwitterMetaGenerator
     */
    public function setTwitterType($value)
    {
        $this->TwitterType = $value;
        return $this;
    }

    /**
     * setTwitterSite
     *
     * @param  $value the value
     * @return TwitterMetaGenerator
     */
    public function setTwitterSite($value)
    {
        $this->TwitterSite = $value;
        return $this;
    }

    /**
     * setTwitterCreator
     *
     * @param  $value the value
     * @return TwitterMetaGenerator
     */
    public function setTwitterCreator($value)
    {
        $this->TwitterCreator = $value;
        return $this;
    }

}
