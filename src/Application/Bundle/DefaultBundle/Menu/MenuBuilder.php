<?php

namespace Application\Bundle\DefaultBundle\Menu;

use Knp\Menu\FactoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
        $menu = $this->factory->createItem('root');

        $menu->setUri($request->getRequestUri());

        $menu->addChild($this->translator->trans('Проекты'), array('route' => 'portfolio_all_projects'));
        $menu->addChild($this->translator->trans('Услуги'), array(
            'route' => 'portfolio_categories_list',
            'routeParameters' => array('slug' => 'web-development'),
        ));
        if ($withIndustries) {
            $menu->addChild($this->translator->trans('__menu.industries'), ['uri' => '#'])
                ->setAttribute('class', 'industry')
                ->setLinkAttribute('class', 'industry__title')
                    ->addChild($this->templating->render('::_sub_menu_industries.html.twig'))
                    ->setAttribute('render', true)
            ;
        }
        $menu->addChild($this->translator->trans('Команда'), array('route' => 'team'));
        $menu->addChild($this->translator->trans('Блог'), array('route' => 'blog'));
        $menu->addChild('Open Source', array('route' => 'opensource'));
        $menu->addChild($this->translator->trans('__menu.vacancies'), array('route' => 'jobs_list'));
        $menu->addChild($this->translator->trans('Контакты'), array('route' => 'contacts'));

        $menu->addChild(
            $this->translator->trans('about_us'),
            [
                'route' => 'show_pdf',
                'routeParameters' => ['pdfFilename' => 'About_Stfalcon_2018.pdf'],
            ]
        )
            ->setLinkAttributes(['class' => 'header-line__btn home-btn home-btn--mob-fluid home-btn--sm home-btn--dark', 'target' => '_blank']);
        $projectsMenu = $menu->getChild($this->translator->trans('Проекты'));
        $projectsMenu->setCurrent($projectsMenu->getUri() === $request->getRequestUri());

        return $menu;
    }
}
