<?php

namespace Application\Bundle\DefaultBundle\Menu;

use Knp\Bundle\MenuBundle\Menu;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

/**
 * Bread crumbs
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class BreadCrumbs extends Menu
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

        $this->addChild('Главная', $router->generate('homepage'));
    }

}