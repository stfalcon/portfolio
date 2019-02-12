<?php

namespace Application\Bundle\DefaultBundle\Menu;

use Knp\Menu\FactoryInterface;
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
    /**
     * @var FactoryInterface Factory interface
     */
    private $factory;

    /**
     * @var TranslatorInterface Translator interface
     */
    private $translator;

    private $extraText = '';

    /** @var EngineInterface */
    private $templating;

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
        $menu = $this->factory->createItem('root');

        $menu->setUri($request->getRequestUri());

        foreach ($this->getMenuItemRoutesRelations() as $index => $menuItemRoutesRelation) {
            if ($withIndustries && self::INDUSTRIES_MENU_INDEX === $index) {
                $menu->addChild($this->translator->trans('__menu.industries'), ['uri' => '#'])
                    ->setCurrent('page_landing' === $currentRoute)
                    ->setAttribute('class', 'industry')
                    ->setLinkAttribute('class', 'industry__title')
                    ->addChild($this->templating->render('::_sub_menu_industries.html.twig'))
                    ->setAttribute('render', true)
                ;
            }

            $isCurrent = $currentRoute === $menuItemRoutesRelation['config']['route'] || (isset($menuItemRoutesRelation['child_routes']) && in_array($currentRoute, $menuItemRoutesRelation['child_routes']));
            $menu->addChild($menuItemRoutesRelation['title'], $menuItemRoutesRelation['config'])->setCurrent($isCurrent);
        }

        return $menu;
    }

    /**
     * @return array
     */
    private function getMenuItemRoutesRelations()
    {
        $relations = [
             [
                'title' => $this->translator->trans('Проекты'),
                'config' => [
                    'route' => 'portfolio_all_projects',
                ],
                'child_routes' => ['portfolio_category_project', 'portfolio_project_view'],
            ],
            [
                'title' => $this->translator->trans('Услуги'),
                'config' => [
                    'route' => 'portfolio_categories_list',
                    'routeParameters' => ['slug' => 'web-development'],
                ],
                'child_routes' => ['portfolio_categories_list'],
            ],
            [
                'title' => $this->translator->trans('Команда'),
                'config' => [
                    'route' => 'team',
                ],
            ],
            [
                'title' => $this->translator->trans('Блог'),
                'config' => [
                    'route' => 'blog',
                ],
                'child_routes' => ['blog_post_view'],
            ],
            [
                'title' => 'Open Source',
                'config' => [
                    'route' => 'opensource',
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
                'title' => $this->translator->trans('Контакты'),
                'config' => [
                    'route' => 'contacts',
                ],
            ],
            [
                'title' => $this->translator->trans('about_us'),
                'config' => [
                    'route' => 'show_pdf',
                    'routeParameters' => ['pdfFilename' => 'About_Stfalcon_2019.pdf'],
                ],
            ],
        ];

        return $relations;
    }
}
