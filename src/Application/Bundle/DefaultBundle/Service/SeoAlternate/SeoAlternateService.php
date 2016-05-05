<?php

namespace Application\Bundle\DefaultBundle\Service\SeoAlternate;

use Application\Bundle\UserBundle\Entity\User;
use Sonata\SeoBundle\Seo\SeoPage;
use Stfalcon\Bundle\BlogBundle\Entity\Post;
use Stfalcon\Bundle\PortfolioBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;

/**
 * SeoAlternateService class
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class SeoAlternateService
{
    /**
     * @var PostAlternateService $postAlternateService Post alternate service
     */
    private $postAlternate;

    /**
     * @var CategoryAlternateService $categoryAlternateService Category alternate service
     */
    private $categoryAlternate;

    /**
     * @var UserAlternateService $userAlternateService User alternate service
     */
    private $userAlternate;

    /**
     * Constructor
     *
     * @param PostAlternateService     $postAlternateService     Post alternate service
     * @param CategoryAlternateService $categoryAlternateService Category alternate service
     * @param UserAlternateService     $userAlternateService     User alternate service
     */
    public function __construct(
        PostAlternateService $postAlternateService,
        CategoryAlternateService $categoryAlternateService,
        UserAlternateService $userAlternateService
    ) {
        $this->postAlternate     = $postAlternateService;
        $this->categoryAlternate = $categoryAlternateService;
        $this->userAlternate     = $userAlternateService;
    }

    /**
     * Add alternate for posts
     *
     * @param Object  $entity  Entity
     * @param SeoPage $seoPage Seo page
     * @param Request $request Request
     */
    public function addAlternate($entity, SeoPage $seoPage, Request $request)
    {
        $service = $this->getAppropriateService($entity);

        if (null !== $service) {
            $service->execute($entity, $seoPage, $request);
        }
    }

    /**
     * Get appropriate service
     *
     * @param Object $entity Entity
     *
     * @return CategoryAlternateService|PostAlternateService|UserAlternateService|null
     */
    private function getAppropriateService($entity)
    {
        switch (true) {
            case $entity instanceof Post:
                return $this->postAlternate;
            case $entity instanceof Category:
                return $this->categoryAlternate;
            case $entity instanceof User:
                return $this->userAlternate;
        }

        return null;
    }
}
