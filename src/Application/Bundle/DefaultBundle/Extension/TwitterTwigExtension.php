<?php

namespace Application\Bundle\DefaultBundle\Extension;

/**
 * Extension to find links in the messages and wrap they to html tags
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class TwitterTwigExtension extends \Twig_Extension
{

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'twit' => new \Twig_Filter_Method($this, 'twit'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'twitter';
    }

    /**
     * Highlights the link and usernames in twitter message
     *
     * @param string $value text of message
     *
     * @return string
     */
    public function twit($value)
    {
        // replace links
        $value = preg_replace('/http:\/\/([\w\.\/\!\#]*)/', '<a href="http://$1" rel="nofollow">$1</a>', $value);

        // replace usernames
        $value = preg_replace('/(\W?)@(\w{1,})/', '$1<a href="http://twitter.com/#!/$2" rel="nofollow">@$2</a>', $value);

        // replace hashes
        $value = preg_replace('/(\W?)#(\w{1,})/', '$1<a href="http://twitter.com/#!/search?q=$2" rel="nofollow">#$2</a>', $value);

        return $value;
    }

}