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
     * @Route("/", name="team")
     * @Template()
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getManager()
                ->getRepository("ApplicationUserBundle:User")->findAllActiveUsers();

        foreach ($users as $user) {
            $user->setInterests(array_values($user->getInterests()));
        }

        $interestsList = User::getInterestsList();

        return array('users' => $users, 'interestsList' => $interestsList);
    }
}