<?php

namespace Application\Bundle\UserBundle\Controller;

use Application\Bundle\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Default controller.
 */
class DefaultController extends Controller
{

    /**
     * Team page
     *
     * @return array()
     * @Route("/team", name="team")
     * @Template()
     */
    public function indexAction()
    {
        $request = $this->get('request');

        $seo = $this->get('sonata.seo.page');
        $seo->generateLangAlternates($request);

        $users = $this->getDoctrine()->getManager()
                ->getRepository("ApplicationUserBundle:User")->findAllActiveUsers();

        $interestsList = User::getInterestsList();

        return array('users' => $users, 'interestsList' => $interestsList);
    }
}