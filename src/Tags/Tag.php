<?php

namespace Syntro\Seo\Tags;

use SilverStripe\Core\Injector\Injectable;

/**
 * Tags are responsible for rendering meta information to the head of a
 * page.
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 */
class Tag
{
    use Injectable;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $tag = 'meta';

    /**
     * __construct
     *
     * @param  string      $name name of this tag, must be unique
     * @param  array       $data the data in this tag
     * @param  null|string $tag  the tag to use. by default, a 'meta' tag is created
     * @return void
     */
    function __construct($name, array $data, $tag = null)
    {
        $this->name = $name;
        $this->data = $data;
        if (!is_null($tag)) {
            $this->tag = $tag;
        }
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * getData
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * getTag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }


    /**
     * forRender - helper to generate the array structure for generating
     * this tag.
     *
     * @return array
     */
    public function forRender()
    {
        return [
            'tag' => $this->getTag(),
            'attributes' => $this->getData()
        ];
    }
}
