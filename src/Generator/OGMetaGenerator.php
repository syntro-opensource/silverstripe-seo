<?php
namespace Syntro\SEOMeta\Generator;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

/**
 * Responsible for generating the open graph meta information
 */
class OGMetaGenerator
{
    use Injectable, Configurable;

    /**
     * @config
     * @var array
     */
    private static $available_types = [
        'website',
        'article'
    ];

    /**
     * @var string|null
     */
    protected $OGName;

    /**
     * @var string|null
     */
    protected $OGTitle;

    /**
     * getOGName
     * @return string
     */
    public function getOGName()
    {
        return $this->OGName;
    }

    /**
     * getOGName
     * @return string
     */
    public function getOGTitle()
    {
        return $this->OGTitle;
    }

    /**
     * process - process the tags and generate
     *
     * @return array  all tags that were able to be processed
     */
    public function process()
    {
        $tags = [];

        // og:name
        if ($this->getOGName()) {
            $tags['og:name'] = [
                'attributes' => [
                    'property' => 'og:name',
                    'content' => $this->getOGName(),
                ],
            ];
        }

        // og:title
        if ($this->getOGName()) {
            $tags['og:title'] = [
                'attributes' => [
                    'property' => 'og:title',
                    'content' => $this->getOGTitle(),
                ],
            ];
        }

        return $tags;

    }


    /**
     * setOGName
     *
     * @param  $value the value
     * @return OGMetaGenerator
     */
    public function setOGName($value)
    {
        $this->OGName = $value;
        return $this;
    }

    /**
     * setOGName
     *
     * @param  $value the value
     * @return OGMetaGenerator
     */
    public function setOGTitle($value)
    {
        $this->OGTitle = $value;
        return $this;
    }

}
