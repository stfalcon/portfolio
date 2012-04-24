<?php

namespace Application\Bundle\DefaultBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Main menu builder.
 * For render menu you can use this code:
 * {{ knp_menu_render('main') }}
 */
class MenuBuilder
{

    private $factory;

    /**
     * Constructor injection
     *
     * @param FactoryInterface $factory
     *
     * @return void
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
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

        $menu->setCurrentUri($request->getRequestUri());

        $menu->addChild('Наши работы', array('route' => 'homepage'));
        $menu->addChild('Блог', array('route' => 'blog'));
        $menu->addChild('Контакты', array('route' => 'contacts'));

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

        $menu->setCurrentUri($request->getRequestUri());

        $menu->addChild('Главная', array('route' => 'homepage'));

        return $menu;
    }
}