<?php
namespace Syntro\SEOMeta\Generator;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Assets\Image;

/**
 * Responsible for generating the open graph meta information
 */
class OtherMetaGenerator
{
    use Injectable, Configurable;


    /**
     * @var string|null
     */
    protected $PublishDate;

    /**
     * @var string|null
     */
    protected $ChangeDate;

    /**
     * @var string|null
     */
    protected $CanonicalURL;


    /**
     * getPublishDate
     * @return string|null
     */
    public function getPublishDate()
    {
        return $this->PublishDate;
    }

    /**
     * getChangeDate
     * @return string|null
     */
    public function getChangeDate()
    {
        return $this->ChangeDate;
    }

    /**
     * getCanonicalURL
     * @return string
     */
    public function getCanonicalURL()
    {
        return $this->CanonicalURL;
    }

    /**
     * process - process the tags and generate
     *
     * @return array  all tags that were able to be processed
     */
    public function process()
    {
        $tags = [];

        // article:published_time
        if ($this->getPublishDate()) {
            $tags['article:published_time'] = [
                'attributes' => [
                    'property' => 'article:published_time',
                    'content' => $this->getPublishDate(),
                ],
            ];
        }

        // article:modified_time
        if ($this->getChangeDate()) {
            $tags['article:modified_time'] = [
                'attributes' => [
                    'property' => 'article:modified_time',
                    'content' => $this->getChangeDate(),
                ],
            ];
        }

        // link[canonical]
        if ($this->getCanonicalURL()) {
            $tags['link-canonical'] = [
                'tag' => 'link',
                'attributes' => [
                    'rel' => 'canonical',
                    'href' => $this->getCanonicalURL()
                ],
            ];
        }


        return $tags;

    }

    /**
     * setPublishDate
     *
     * @param  $value the value
     * @return OtherMetaGenerator
     */
    public function setPublishDate($value)
    {
        $this->PublishDate = $value;
        return $this;
    }

    /**
     * setChangeDate
     *
     * @param  $value the value
     * @return OtherMetaGenerator
     */
    public function setChangeDate($value)
    {
        $this->ChangeDate = $value;
        return $this;
    }

    /**
     * setCanonicalURL
     *
     * @param  $value the value
     * @return OtherMetaGenerator
     */
    public function setCanonicalURL($value)
    {
        $this->CanonicalURL = $value;
        return $this;
    }

}
