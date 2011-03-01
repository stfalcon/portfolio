<?php

namespace Application\PortfolioBundle\Controller;

use Application\PortfolioBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    /**
     * Categories/projects list
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery('SELECT c FROM PortfolioBundle:Category c');
        $categories = $query->getResult();

        return $this->render('PortfolioBundle:Default:index.html.php', array(
            'categories' => $categories
        ));
    }

}