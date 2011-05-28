<?php

namespace Application\DefaultBundle\Menu;

use Knplabs\Bundle\MenuBundle\Menu;
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

        $this->setCurrentUri($request->getRequestUri());
        $this->setCurrentAsLink(false);

        $this->addChild('Услуги', $router->generate('homepage'));
        $this->addChild('Блог', $router->generate('blog_post_index'));
        $this->addChild('Контакты', $router->generate('portfolioDefaultContacts'));
    }

}