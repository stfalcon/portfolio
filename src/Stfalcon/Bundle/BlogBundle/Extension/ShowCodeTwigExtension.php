<?php

namespace Stfalcon\Bundle\BlogBundle\Extension;

/**
 * Show code with style
 */
class ShowCodeTwigExtension extends \Twig_Extension
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
            'showCode' => new \Twig_Filter_Method($this, 'showCode'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'show_code';
    }

    /**
     * Show code with style
     *
     * @param string $value Full source text
     *
     * @return string
     */
    public function showCode($value)
    {
        // update text html code
        require_once __DIR__ . '/../Resources/vendor/geshi/geshi.php';

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