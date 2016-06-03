<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Application\Bundle\DefaultBundle\Helpers\SeoOpenGraphEnum;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Stfalcon\Bundle\BlogBundle\Entity\Tag;
use Stfalcon\Bundle\BlogBundle\Entity\TagTranslation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $seo = $this->get('sonata.seo.page');
        $seo->addMeta(
            'property',
            'og:url',
            $this->generateUrl(
                $request->get('_route'),
                [
                    'text' => $text,
                ],
                true
            )
        )
            ->addMeta('property', 'og:type', SeoOpenGraphEnum::WEBSITE);

        $tagTranslation = $tagTranslationRepository->findOneBy(['content' => $text]);
        if (null === $tagTranslation) {
            $tag = $tagRepository->findOneBy(['text' => $text]);
        } else {
            $tag = $tagTranslation->getObject();
        }

        if (null === $tag) {
            throw new NotFoundHttpException();
        }

        $query = $postRepository->findPostsByTagAsQuery($tag, $request->getLocale());
        $posts = $this->get('knp_paginator')->paginate($query, $page, 10);

        if (count($posts) > 1) {
            return $this->_getRequestDataWithDisqusShortname([
                'tag'   => $tag,
                'posts' => $posts,
            ]);
        } else {
            return $this->redirect($this->generateUrl('blog'));
        }
    }
}
