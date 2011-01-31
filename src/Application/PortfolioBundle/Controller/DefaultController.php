<?php

namespace Application\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//use Application\PortfolioBundle\Entity\Project;
use Application\PortfolioBundle\Entity\Category;
//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{

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