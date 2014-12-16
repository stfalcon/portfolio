<?php

namespace Application\Bundle\DefaultBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Main menu builder.
 * For render menu you can use this code:
 * {{ knp_menu_render('main') }}
 */
class MenuBuilder
{

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * Constructor injection
     *
     * @param FactoryInterface $factory
     * @param Translator       $translator
     */
    public function __construct(FactoryInterface $factory, Translator $translator)
    {
        $this->factory = $factory;
        $this->translator = $translator;
    }

    /**
     * Main menu
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        $menu->setUri($request->getRequestUri());

        $menu->addChild($this->translator->trans('Проекты'), array('route' => 'portfolio_all_projects'));
        $menu->addChild($this->translator->trans('Услуги'), array('route' => 'portfolio_categories_list'));
        $menu->addChild($this->translator->trans('Команда'), array('route' => 'team'));
        $menu->addChild($this->translator->trans('Блог'), array('route' => 'blog'));
        $menu->addChild($this->translator->trans('Контакты'), array('route' => 'contacts'));

        return $menu;
    }

    /**
     * Breadcrumbs
     *
     * @param Request $request
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