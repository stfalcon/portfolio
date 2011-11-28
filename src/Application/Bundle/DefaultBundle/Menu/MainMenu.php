<?php

namespace Application\Bundle\DefaultBundle\Menu;

use Knp\Bundle\MenuBundle\Menu;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

/**
 * Main menu
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class MainMenu extends Menu
{

    /**
     * Use to set request and router objects into this class
     *
     * @param Request $request Symfony Request object
     * @param Router  $router  Symfony Router object
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