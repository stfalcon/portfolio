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

        $url = $request->getRequestUri();
        $menu->setUri($url);
        $this->removePaginationFromUrlStr($url);
        $categories = $this->postCategoryRepository->findAll();
        $childCategory = $menu->addChild($this->translator->trans('__last_posts'), ['route' => 'blog'])
            ->setLinkAttribute('class', 'tab-title');
        $this->checkCurrent($childCategory, $url);
        /** @var PostCategory $category */
        foreach ($categories as $category) {
            if ($category->getPosts()->count() > 0) {
                $childCategory = $menu->addChild($category->getName(), ['route' => 'blog', 'routeParameters' => ['title' => $category->getName()]])
                    ->setLinkAttribute('class', 'tab-title');
                $this->checkCurrent($childCategory, $url);
            }
        }

        return $menu;
    }

    /**
     * @param ItemInterface $childCategory
     * @param string        $url
     */
    private function checkCurrent(ItemInterface $childCategory, $url)
    {
        $childCategory->setCurrent(1 === preg_match('/'.preg_quote($url, '/').'$/', $childCategory->getUri()));
        if ($childCategory->isCurrent()) {
            $childCategory->setLinkAttribute('class', 'tab-title active');
        }
    }

    /**
     * @param string $url
     */
    private function removePaginationFromUrlStr(&$url)
    {
        $urlArray = explode('/', $url);
        if (0 < preg_match('/[0-9]$/', $urlArray[count($urlArray)-1])) {
            array_pop($urlArray);
            if ('all' === strtolower($urlArray[count($urlArray)-1])) {
                array_pop($urlArray);
            }
            $url = implode('/', $urlArray);
        }
    }
}
