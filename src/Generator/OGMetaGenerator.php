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

}
