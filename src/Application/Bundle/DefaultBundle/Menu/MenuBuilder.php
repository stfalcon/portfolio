<?php

namespace Application\Bundle\DefaultBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Main menu builder.
 * For render menu you can use this code:
 * {{ knp_menu_render('main') }}.
 */
class MenuBuilder
{
    const INDUSTRIES_MENU_INDEX = 2;
    const MENU_ICON = '<svg width="11" height="7" viewBox="0 0 11 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <use xlink:href="#icon-arrow"/>
                        </svg>';
    /**
     * @var FactoryInterface Factory interface
     */
    private $factory;

    /**
     * @var TranslatorInterface Translator interface
     */
    private $translator;

    /** @var EngineInterface */
    private $templating;

    private $types = [
        [
            'attributes' => ['class' => 'navigation__item'],
            'link_attributes' => ['class' => 'navigation__link'],
            'label_attributes' => ['class' => 'navigation__link'],
            'children_attributes' => ['class' => 'sub-navigation'],
        ],
        [
            'attributes' => ['class' => 'sub-navigation__item'],
            'link_attributes' => ['class' => 'sub-navigation__link'],
            'label_attributes' => ['class' => 'sub-navigation__link'],
            'children_attributes' => [],
        ],
    ];

    /**
     * Constructor.
     *
     * @param FactoryInterface    $factory    Factory interface
     * @param TranslatorInterface $translator Translator interface
     * @param EngineInterface     $templating
     */
    public function __construct(FactoryInterface $factory, TranslatorInterface $translator, EngineInterface $templating)
    {
        $this->factory = $factory;
        $this->translator = $translator;
        $this->templating = $templating;
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
        return $this->getMainMenu($request);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function createFooterMainMenu(Request $request)
    {
        return $this->getMainMenu($request, false);
    }

    /**
     * @param Request $request
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createServiceMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setUri($request->getRequestUri());

        $menu->addChild(
            $this->translator->trans('Разработка веб-сайтов'),
            [
                'route' => 'portfolio_categories_list',
                'routeParameters' => ['slug' => 'web-development'],
            ]
        );
        $menu->addChild(
            $this->translator->trans('Дизайн сайтов и интерфейсов'),
            [
                'route' => 'portfolio_categories_list',
                'routeParameters' => ['slug' => 'web-design'],
            ]
        );
        $menu->addChild(
            $this->translator->trans('Мобильная разработка'),
            [
                'route' => 'portfolio_categories_list',
                'routeParameters' => ['slug' => 'mobile-development'],
            ]
        );
        $menu->addChild(
            $this->translator->trans('main.promo.title2_1'),
            [
                'route' => 'page_landing',
                'routeParameters' => ['type' => 'logistics'],
            ]
        );
        $menu->addChild(
            $this->translator->trans('main.promo.title2_2'),
            [
                'route' => 'page_landing',
                'routeParameters' => ['type' => 'travel'],
            ]
        );
        $menu->addChild(
            $this->translator->trans('main.promo.title2_3'),
            [
                'route' => 'page_landing',
                'routeParameters' => ['type' => 'healthcare'],
            ]
        );
        $menu->addChild(
            $this->translator->trans('main.promo.title2_4'),
            [
                'route' => 'page_landing',
                'routeParameters' => ['type' => 'e-commerce'],
            ]
        );

        return $menu;
    }

    /**
     * Breadcrumbs.
     *
     * @param Request $request Request
     *
     * @return mixed
     */
    public function createBreadcrumbsMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        $menu->setUri($request->getRequestUri());

        $menu->addChild($this->translator->trans('Главная'), array('route' => 'homepage'));

        return $menu;
    }

    /**
     * @param Request $request
     * @param bool    $withIndustries
     *
     * @return mixed
     */
    private function getMainMenu(Request $request, $withIndustries = true)
    {
        $currentRoute = $request->get('_route');
        $menu = $this->factory->createItem('root', [
            'childrenAttributes' => [
                'class' => 'navigation',
            ],
        ]);

        $menu->setUri($request->getRequestUri());

        foreach ($this->getMenuItemRoutesRelations() as $menuItem) {
//            if ($withIndustries && self::INDUSTRIES_MENU_INDEX === $index) {
//                $menu->addChild($this->translator->trans('__menu.industries'), ['uri' => '#'])
//                    ->setCurrent('page_landing' === $currentRoute)
//                    ->setAttribute('class', 'industry')
//                    ->setLinkAttribute('class', 'industry__title')
//                    ->addChild($this->templating->render('::_sub_menu_industries.html.twig'))
//                    ->setAttribute('render', true)
//                ;
//            }

            $this->addMenu($menu, $menuItem, $currentRoute);
        }

        return $menu;
    }

    /**
     * @param ItemInterface $menu
     * @param array         $menuItem
     * @param string        $currentRoute
     * @param bool          $isSubMenu
     */
    private function addMenu(ItemInterface $menu, array $menuItem, $currentRoute, $isSubMenu = false)
    {
        $isCurrent = (isset($menuItem['config']['route']) && $currentRoute === $menuItem['config']['route']) || (isset($menuItem['child_routes']) && \in_array($currentRoute, $menuItem['child_routes']));

        $menu->addChild($menuItem['title'], $menuItem['config'])->setCurrent($isCurrent);

        $typeIndex = (int) $isSubMenu;
        $menu[$menuItem['title']]->setAttributes($this->types[$typeIndex]['attributes'])
            ->setLinkAttributes($this->types[$typeIndex]['link_attributes'])
            ->setLabelAttributes($this->types[$typeIndex]['label_attributes']);

        if (isset($menuItem['children'])) {
            $menu[$menuItem['title']]->setChildrenAttributes($this->types[$typeIndex]['children_attributes']);
            foreach ($menuItem['children'] as $child) {
                $this->addMenu($menu[$menuItem['title']], $child, $currentRoute, true);
            }
        }
    }

    /**
     * @return array
     */
    private function getMenuItemRoutesRelations()
    {
        $relations = [
            [
                'title' => $this->translator->trans('__menu.works').self::MENU_ICON,
                'config' => [],
                'children' => [
                    [
                        'title' => $this->translator->trans('__menu.projects'),
                        'config' => [
                            'route' => 'portfolio_all_projects',
                        ],
                        'child_routes' => ['portfolio_category_project', 'portfolio_project_view'],
                    ],
                    [
                        'title' => $this->translator->trans('__menu.services'),
                        'config' => [
                            'route' => 'portfolio_categories_list',
                            'routeParameters' => ['slug' => 'web-development'],
                        ],
                        'child_routes' => ['portfolio_categories_list'],
                    ],
                    [
                        'title' => 'Open Source',
                        'config' => [
                            'route' => 'opensource',
                        ],
                    ],
                ],
            ],
            [
                'title' => $this->translator->trans('__menu.industries').self::MENU_ICON,
                'config' => [],
                'children' => [
                    [
                        'title' => $this->translator->trans('main.promo.title2_1'),
                        'config' => [
                            'route' => 'page_landing',
                            'routeParameters' => ['type' => 'logistics'],
                        ],
                    ],
                    [
                        'title' => $this->translator->trans('main.promo.title2_2'),
                        'config' => [
                            'route' => 'page_landing',
                            'routeParameters' => ['type' => 'travel'],
                        ],
                    ],
                    [
                        'title' => $this->translator->trans('main.promo.title2_3'),
                        'config' => [
                            'route' => 'page_landing',
                            'routeParameters' => ['type' => 'healthcare'],
                        ],
                    ],
                    [
                        'title' => $this->translator->trans('main.promo.title2_4'),
                        'config' => [
                            'route' => 'page_landing',
                            'routeParameters' => ['type' => 'e-commerce'],
                        ],
                    ],
                ],
            ],
            [
                'title' => $this->translator->trans('__menu.blog'),
                'config' => [
                    'route' => 'blog',
                ],
                'child_routes' => ['blog_post_view'],
            ],
            [
                'title' => $this->translator->trans('__menu.company').self::MENU_ICON,
                'config' => [],
                'children' => [
                    [
                        'title' => $this->translator->trans('__menu.team'),
                        'config' => [
                            'route' => 'team',
                        ],
                    ],
                    [
                        'title' => $this->translator->trans('__menu.vacancies'),
                        'config' => [
                            'route' => 'jobs_list',
                        ],
                        'child_routes' => ['jobs_job_view'],
                    ],
                    [
                        'title' => $this->translator->trans('__menu.about_us').'<span class="type-hint">PDF</span>',
                        'config' => [
                            'route' => 'show_pdf',
                            'routeParameters' => ['pdfFilename' => 'About_Stfalcon_2019.pdf'],
                        ],
                    ],
                ],
            ],
        ];

        return $relations;
    }
}
