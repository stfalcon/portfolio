<?php

namespace Stfalcon\Bundle\BlogBundle\Extension;

/**
 * Cut text. Replace <!--more--> to "Read more"
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class ReadMoreTwigExtension extends \Twig_Extension
{

    public static $separator = '<!--more-->';

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'cutMore' => new \Twig_Filter_Method($this, 'cutMore'),
            'moreToSpan' => new \Twig_Filter_Method($this, 'moreToSpan'),
        );
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'hasMore' => new \Twig_Function_Method($this, 'hasMore'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'read_more';
    }

    /**
     * Cut text before "more" tag
     *
     * @param string $value Full source text
     *
     * @return string
     */
    public function cutMore($value)
    {
        $posMore = ((int) strpos($value, self::$separator));
        if ($posMore) {
            return substr($value, 0, $posMore);
        }
        return $value;
    }

    /**
     * Check or text has "more" tag
     *
     * @param string $value Full source text
     *
     * @return boolean
     */
    public function hasMore($value)
    {
        return (bool) substr($value, 0, (int) strpos($value, self::$separator));
    }

    /**
     * Replace <!--more--> to <span id="more"></span>
     *
     * @param string $value Full source text
     *
     * @return string
     */
    public function moreToSpan($value)
    {
        return str_replace(self::$separator, '<span id="more"></span>', $value);
    }

}