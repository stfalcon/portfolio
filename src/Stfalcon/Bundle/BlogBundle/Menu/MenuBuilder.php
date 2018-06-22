<?php

namespace  Stfalcon\Bundle\BlogBundle\Menu;

use Doctrine\ORM\EntityRepository;
use Knp\Menu\FactoryInterface;
use Stfalcon\Bundle\BlogBundle\Entity\PostCategory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Main menu builder.
 */
class MenuBuilder
{
    /**
     * @var FactoryInterface $factory Factory interface
     */
    private $factory;

    /**
     * @var EntityRepository
     */
    private $postCategoryRepository;

    /** @var TranslatorInterface  */
    private $translator;

    /**
     * Constructor
     *
     * @param FactoryInterface    $factory
     * @param EntityRepository    $postCategoryRepository
     * @param TranslatorInterface $translator
     */
    public function __construct(FactoryInterface $factory, EntityRepository $postCategoryRepository, TranslatorInterface $translator)
    {
        $this->factory = $factory;
        $this->postCategoryRepository = $postCategoryRepository;
        $this->translator = $translator;
    }

    /**
     * Main menu
     *
     * @param Request $request Request
     *
     * @return mixed
     */
    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        $menu->setUri($request->getRequestUri());
        $categories = $this->postCategoryRepository->findAll();
        $menu->addChild($this->translator->trans('__last_posts'), ['route' => 'blog']);
        /** @var PostCategory $category */
        foreach ($categories as $category) {
            if ($category->getPosts()->count() > 0) {
                $menu->addChild($category->getName(), ['route' => 'blog', 'routeParameters' => ['title' => $category->getName()]]);
            }
        }

        return $menu;
    }
}
