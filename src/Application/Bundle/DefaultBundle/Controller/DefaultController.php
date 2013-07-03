<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Default controller. For single actions for project
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class DefaultController extends Controller
{

    /**
     * Categories/projects lilowerst
     *
     * @return array()
     * @Cache(expires="tomorrow")
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $categories = $this->getDoctrine()->getManager()
                ->getRepository("StfalconPortfolioBundle:Category")->getAllCategories();


        return array('categories' => $categories);
    }


    /**
     * Contacts page
     *
     * @return array()
     * @Template()
     * @Route("/contacts", name="contacts")
     */
    public function contactsAction()
    {
        // @todo: refact
        if ($this->has('application_default.menu.breadcrumbs')) {
            $breadcrumbs = $this->get('application_default.menu.breadcrumbs');
            $breadcrumbs->addChild('Контакты')->setCurrent(true);
        }

        return array();
    }

}