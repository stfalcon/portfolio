<?php

namespace Application\Bundle\DefaultBundle\Service\SeoAlternate;

use Stfalcon\Bundle\BlogBundle\Entity\Post;

/**
 * PostAlternateService class
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class PostAlternateService extends AbstractSeoAlternateService
{
    /**
     * Get identifier
     *
     * @param Post $post Post
     *
     * @return string
     */
    public function getIdentifier($post)
    {
        return $post->getSlug();
    }

    /**
     * Get translation
     *
     * @param string $slug   Slug
     * @param string $locale Locale
     *
     * @return Post|null
     */
    public function getTranslation($slug, $locale)
    {
        return $this->em->getRepository('StfalconBlogBundle:Post')->findPostBySlugInLocale($slug, $locale);
    }
}
