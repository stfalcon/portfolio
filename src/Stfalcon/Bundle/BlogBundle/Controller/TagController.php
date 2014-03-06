<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Stfalcon\Bundle\BlogBundle\Entity\Tag;

/**
 * TagController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class TagController extends AbstractController
{

    /**
     * View tag
     *
     * @param Tag $tag
     * @param int $page
     *
     * @return array
     *
     * @Route("/blog/tag/{text}/{title}/{page}", name="blog_tag_view",
     *      requirements={"page"="\d+", "title"="page"},
     *      defaults={"page"="1", "title"="page"})
     * @Template()
     */
    public function viewAction(Tag $tag, $page)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('StfalconBlogBundle:Post');
        $query = $repository->findPostsByTagAsQuery($tag);
        $posts = $this->get('knp_paginator')
            ->paginate($query, $page, 10);

        return $this->_getRequestDataWithDisqusShortname(array(
            'tag' => $tag,
            'posts' => $posts,
        ));
    }

}