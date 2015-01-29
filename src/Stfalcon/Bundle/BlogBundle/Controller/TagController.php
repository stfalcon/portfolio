<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Stfalcon\Bundle\BlogBundle\Entity\Tag;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     * @param Tag     $tag
     * @param int     $page page number
     *
     * @return array
     *
     * @Route("/blog/tag/{text}/{title}/{page}", name="blog_tag_view",
     *      requirements={"page"="\d+", "title"="page"},
     *      defaults={"page"="1", "title"="page"})
     * @Template()
     */
    public function viewAction(Request $request, Tag $tag, $page)
    {
        $translator = $this->get('translator');
        $repository = $this->getDoctrine()->getManager()->getRepository('StfalconBlogBundle:Post');
        $query = $repository->findPostsByTagAsQuery($tag, $request->getLocale());
        $posts = $this->get('knp_paginator')
            ->paginate($query, $page, 10);

        return $this->_getRequestDataWithDisqusShortname(array(
            'tag' => $tag,
            'posts' => $posts,
        ));
    }

}