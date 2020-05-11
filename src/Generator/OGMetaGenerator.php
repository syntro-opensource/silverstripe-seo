<?php
namespace Syntro\SEOMeta\Generator;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\FieldType\DBHTMLText;

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
     * @var string|null
     */
    protected $OGUrl;

    /**
     * @var string|null
     */
    protected $OGDescription;

    /**
     * getOGName
     * @return string
     */
    public function getOGName()
    {
        return $this->OGName;
    }

    /**
     * getOGTitle
     * @return string
     */
    public function getOGTitle()
    {
        return $this->OGTitle;
    }

    /**
     * getOGUrl
     * @return string
     */
    public function getOGUrl()
    {
        return $this->OGUrl;
    }

    /**
     * getOGDescription
     * @return string
     */
    public function getOGDescription()
    {
        $obj = DBHTMLText::create();

        if (!$this->OGDescription) {
            return null;
        }

        return $obj->setValue($this->OGDescription)->LimitCharacters(297);
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
        if ($this->getOGTitle()) {
            $tags['og:title'] = [
                'attributes' => [
                    'property' => 'og:title',
                    'content' => $this->getOGTitle(),
                ],
            ];
        }

        // og:url
        if ($this->getOGUrl()) {
            $tags['og:url'] = [
                'attributes' => [
                    'property' => 'og:url',
                    'content' => $this->getOGUrl(),
                ],
            ];
        }

        // og:description
        if ($this->getOGDescription()) {
            $tags['og:description'] = [
                'attributes' => [
                    'property' => 'og:description',
                    'content' => $this->getOGDescription(),
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

    /**
     * setOGUrl
     *
     * @param  $value the value
     * @return OGMetaGenerator
     */
    public function setOGUrl($value)
    {
        $this->OGUrl = $value;
        return $this;
    }

    /**
     * setOGDescription
     *
     * @param  $value the value
     * @return OGMetaGenerator
     */
    public function setOGDescription($value)
    {
        $this->OGDescription = $value;
        return $this;
    }

}
