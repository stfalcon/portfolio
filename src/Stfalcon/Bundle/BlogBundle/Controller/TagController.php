<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Stfalcon\Bundle\BlogBundle\Entity\Tag;
use Stfalcon\Bundle\BlogBundle\Entity\TagTranslation;
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
     * @param Request $request Request
     * @param string  $text    Tag text
     * @param int     $page    Page number
     *
     * @return array
     *
     * @Route("/blog/tag/{text}/{title}/{page}", name="blog_tag_view",
     *      requirements={"page"="\d+", "title"="page"},
     *      defaults={"page"="1", "title"="page"})
     *
     * @Template()
     */
    public function viewAction(Request $request, $text, $page)
    {
        $em = $this->getDoctrine()->getManager();

        $postRepository           = $em->getRepository('StfalconBlogBundle:Post');
        $tagTranslationRepository = $em->getRepository('StfalconBlogBundle:TagTranslation');
        $tagRepository            = $em->getRepository('StfalconBlogBundle:Tag');

        $tagTranslation = $tagTranslationRepository->findOneBy(['content' => $text]);
        if (null === $tagTranslation) {
            $tag = $tagRepository->findOneBy(['text' => $text]);
        } else {
            $tag = $tagTranslation->getObject();
        }

        $query = $postRepository->findPostsByTagAsQuery($tag, $request->getLocale());
        $posts = $this->get('knp_paginator')->paginate($query, $page, 10);

        if (count($posts) > 1) {
            return $this->_getRequestDataWithDisqusShortname(array(
                'tag' => $tag,
                'posts' => $posts,
            ));

        } else {
            return $this->redirect($this->generateUrl('blog'));
        }
    }
}
