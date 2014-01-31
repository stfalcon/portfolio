<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * TagController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class AbstractController extends Controller
{

    /**
     * Added disqus_shortname to request data array
     *
     * @param array $requestData
     * @return array
     */
    protected function _getRequestDataWithDisqusShortname($requestData)
    {
        $config = $this->container->getParameter('stfalcon_blog.config');
        return array_merge(
            $requestData,
            array('disqus_shortname' => $config['disqus_shortname'])
        );
    }

}