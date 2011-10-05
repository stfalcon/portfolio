<?php

namespace Application\Bundle\DefaultBundle\Extension;

class TwitterTwigExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            'twit' => new \Twig_Filter_Method($this, 'twit'),
        );
    }
    
    public function getName()
    {
        return 'twitter';
    }
    
    /**
     * Highlights the link and usernames in twitter message
     *
     * @param string $value 
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