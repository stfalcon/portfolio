<?php

namespace Application\Bundle\DefaultBundle\Menu;

use Knp\Bundle\MenuBundle\Menu;
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

        $this->setCurrentUri($request->getRequestUri());
        $this->setCurrentAsLink(false);

        $this->addChild('Наши работы', $router->generate('homepage'));
        $this->addChild('Блог', $router->generate('blog'));
        $this->addChild('Контакты', $router->generate('contacts'));
    }

}