<?php

namespace  Stfalcon\Bundle\BlogBundle\Menu;

use Doctrine\ORM\EntityRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Stfalcon\Bundle\BlogBundle\Entity\PostCategory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Main menu builder.
 */
class MenuBuilder
{
    /**
     * @var FactoryInterface Factory interface
     */
    private $factory;

    /**
     * @var EntityRepository
     */
    private $postCategoryRepository;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * Constructor.
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
     * Main menu.
     *
     * @param Request $request Request
     *
     * @return mixed
     */
    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem(
            'root',
            [
                'childrenAttributes' => [
                        'class' => 'tabs-header tabs-projects',
                    ],
            ]
        );

        $menu->setUri($request->getRequestUri());
        $categories = $this->postCategoryRepository->findAll();
        $childCategory = $menu->addChild($this->translator->trans('__last_posts'), ['route' => 'blog'])
            ->setLinkAttribute('class', 'tab-title');
        $this->checkCurrent($childCategory, $request);
        /** @var PostCategory $category */
        foreach ($categories as $category) {
            if ($category->getPosts()->count() > 0) {
                $childCategory = $menu->addChild($category->getName(), ['route' => 'blog', 'routeParameters' => ['title' => $category->getName()]])
                    ->setLinkAttribute('class', 'tab-title');
                $this->checkCurrent($childCategory, $request);
            }
        }

        return $menu;
    }

    /**
     * @param ItemInterface $childCategory
     * @param Request       $request
     */
    private function checkCurrent(ItemInterface $childCategory, Request $request)
    {
        $childCategory->setCurrent(1 === preg_match('/'.preg_quote($childCategory->getUri(), '/').'/', $request->getRequestUri()));
        if ($childCategory->isCurrent()) {
            $childCategory->setLinkAttribute('class', 'tab-title active');
        }
    }
}
