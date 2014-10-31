<?php

namespace Stfalcon\Bundle\BlogBundle\Extension;

/**
 * Highlight code with style
 */
class HighlightCodeTwigExtension extends \Twig_Extension
{

    public static $pattern = '/<pre lang="(.*?)">\r?\n?(.*?)\r?\n?\<\/pre>/is';

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'highlightCode' => new \Twig_Filter_Method($this, 'highlightCode'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'highlight_code';
    }

    /**
     * Highlight code with style
     *
     * @param string $value Full source text
     *
     * @return string
     */
    public function highlightCode($value)
    {
        $text = preg_replace_callback(
            self::$pattern,
            function($data) {
                $geshi = new \GeSHi($data[2], $data[1]);

                return $geshi->parse_code();
            }, $value
        );

        return $text;
    }
}