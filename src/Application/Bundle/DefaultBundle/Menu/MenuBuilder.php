<?php

namespace Application\Bundle\DefaultBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Main menu builder.
 * For render menu you can use this code:
 * {{ knp_menu_render('main') }}
 */
class MenuBuilder
{
    /**
     * @var FactoryInterface $factory Factory interface
     */
    private $factory;

    /**
     * @var TranslatorInterface $translator Translator interface
     */
    private $translator;

    /**
     * Constructor
     *
     * @param FactoryInterface    $factory    Factory interface
     * @param TranslatorInterface $translator Translator interface
     */
    public function __construct(FactoryInterface $factory, TranslatorInterface $translator)
    {
        $this->factory = $factory;
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

        $menu->addChild($this->translator->trans('Проекты'), array('route' => 'portfolio_all_projects'));
        $menu->addChild($this->translator->trans('Услуги'), array(
            'route'           => 'portfolio_categories_list',
            'routeParameters' => array('slug' => 'web-development'),
        ));
        $menu->addChild($this->translator->trans('Команда'), array('route' => 'team'));
        $menu->addChild($this->translator->trans('Блог'), array('route' => 'blog'));
        if ('ru' === $request->getLocale()) {
            $menu->addChild($this->translator->trans('Вакансии'), array('route' => 'jobs_list'));
        }
        $menu->addChild($this->translator->trans('Контакты'), array('route' => 'contacts'));
        $menu->addChild(
            $this->translator->trans('about_us'),
            [
                'route' => 'show_pdf',
                'routeParameters' => ['pdfFilename' => 'About_Stfalcon_2018.pdf'],
            ]
        )
            ->setLinkAttributes(['class' => 'header-line__btn home-btn home-btn--sm home-btn--dark', 'target' => '_blank']);

        $projectsMenu = $menu->getChild($this->translator->trans('Проекты'));
        $projectsMenu->setCurrent($projectsMenu->getUri() === $request->getRequestUri());

        return $menu;
    }

    /**
     * Breadcrumbs
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
}
