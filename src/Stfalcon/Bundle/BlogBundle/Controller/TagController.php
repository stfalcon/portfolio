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
     * @Route("/blog/tag/{text}/{title}/{page}", name="blog_tag_view",
     *      requirements={"page"="\d+", "title"="page"},
     *      defaults={"page"="1", "title"="page"})
     * @Template()
     *
     * @param Tag $tag
     * @param int $page page number
     *
     * @return array
     */
    public function viewAction(Tag $tag, $page)
    {
        $posts = $this->get('knp_paginator')
            ->paginate($tag->getPosts(), $page, 10);

        if ($this->has('menu.breadcrumbs')) {
            $breadcrumbs = $this->get('menu.breadcrumbs');
            $breadcrumbs->addChild('Блог', $this->get('router')->generate('blog'));
            $breadcrumbs->addChild($tag->getText())->setIsCurrent(true);
        }

        return $this->_getRequestDataWithDisqusShortname(array(
            'tag' => $tag,
            'posts' => $posts,
        ));
    }

}