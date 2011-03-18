<?php

namespace Application\PortfolioBundle\Menu;

use Knplabs\MenuBundle\Menu;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class MainMenu extends Menu
{
    /**
     * @param Request $request
     * @param Router $router
     */
    public function __construct(Request $request, Router $router)
    {
        parent::__construct();
//        parent::__construct(array(), 'Application\PortfolioBundle\Menu\MainMenuItem');

        // @todo: css refact
        $this->setAttribute('class', 'headerMenu');
        $this->setCurrentUri($request->getRequestUri());

        $this->addChild(new MainMenuItem('Услуги', $router->generate('homepage')));
        $this->addChild('Контакты', $router->generate('portfolioDefaultContacts'));
    }
    
}