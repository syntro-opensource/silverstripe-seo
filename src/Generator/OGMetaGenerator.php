<?php
namespace Syntro\SEOMeta\Generator;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Assets\Image;

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
     * @var string|null
     */
    protected $OGType;


    /**
     * @var int|null
     */
    protected $OGImage_w;

    /**
     * @var int|null
     */
    protected $OGImage_h;

    /**
     * @var Image|null
     */
    protected $OGImage;

    /**
     * getOGName
     * @return string|null
     */
    public function getOGName()
    {
        return $this->OGName;
    }

    /**
     * getOGTitle
     * @return string|null
     */
    public function getOGTitle()
    {
        return $this->OGTitle;
    }

    /**
     * getOGUrl
     * @return string|null
     */
    public function getOGUrl()
    {
        return $this->OGUrl;
    }

    /**
     * getOGImage
     * @return Image|null
     */
    public function getOGImage()
    {
        return $this->OGImage;
    }
    /**
     * getOGImage_w
     * @return int|null
     */
    public function getOGImage_w()
    {
        return $this->OGImage_w;
    }
    /**
     * getOGImage_h
     * @return int|null
     */
    public function getOGImage_h()
    {
        return $this->OGImage_h;
    }

    /**
     * getOGDescription
     * @return string|null
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
     * getOGType
     * @return string
     */
    public function getOGType()
    {
        if ($this->OGType) {
            return $this->OGType;
        }
        return 'website';
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

        // og:type
        if ($this->getOGType()) {
            $tags['og:type'] = [
                'attributes' => [
                    'property' => 'og:type',
                    'content' => $this->getOGType(),
                ],
            ];
        }

        // og:image
        if ($this->getOGImage()) {
            $tags['og:image'] = [
                'attributes' => [
                    'property' => 'og:image',
                    'content' => $this->getOGImage()->getAbsoluteURL(),
                ],
            ];
        }

        // og:image:width
        if ($this->getOGImage() && $this->getOGImage_w()) {
            $tags['og:image:width'] = [
                'attributes' => [
                    'property' => 'og:image:width',
                    'content' => $this->getOGImage_w(),
                ],
            ];
        }

        // og:image:height
        if ($this->getOGImage() && $this->getOGImage_h()) {
            $tags['og:image:height'] = [
                'attributes' => [
                    'property' => 'og:image:height',
                    'content' => $this->getOGImage_h(),
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

    /**
     * setOGType
     *
     * @param  $value the value
     * @return OGMetaGenerator
     */
    public function setOGType($value)
    {
        $this->OGType = $value;
        return $this;
    }

    /**
     * setOGImage_w
     *
     * @param  int $value the value
     * @return OGMetaGenerator
     */
    public function setOGImage_w($value)
    {
        $this->OGImage_w = $value;
        return $this;
    }

    /**
     * setOGImage_h
     *
     * @param  int $value the value
     * @return OGMetaGenerator
     */
    public function setOGImage_h($value)
    {
        $this->OGImage_h = $value;
        return $this;
    }

    /**
     * setOGImage
     *
     * @param  Image $value the value
     * @return OGMetaGenerator
     */
    public function setOGImage($value)
    {
        if (is_null($value)) {
            $this->OGImage = $value;
            return $this;
        }
        $this->setOGImage_w($value->getWidth());
        $this->setOGImage_h($value->getHeight());
        $this->OGImage = $value;
        return $this;
    }

}
