<?php

namespace Application\Bundle\UserBundle\Controller;

use Application\Bundle\DefaultBundle\Helpers\SeoOpenGraphEnum;
use Application\Bundle\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Default controller.
 */
class DefaultController extends Controller
{

    /**
     * Team page
     *
     * @param Request $request Request
     *
     * @return array()
     * @Route("/team", name="team")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $users = $this->getDoctrine()
                      ->getRepository('ApplicationUserBundle:User')
                      ->findAllActiveUsers();

        $interestsList = User::getInterestsList();

        $seo = $this->get('sonata.seo.page');
        $seo
            ->addMeta('property', 'og:url', $this->generateUrl($request->get('_route'), [], true))
            ->addMeta('property', 'og:type', SeoOpenGraphEnum::WEBSITE);

        return [
            'users'         => $users,
            'interestsList' => $interestsList,
        ];
    }
}
