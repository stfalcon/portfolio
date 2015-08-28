<?php
namespace Application\Bundle\DefaultBundle\Controller;

use Application\Bundle\DefaultBundle\Form\Type\DirectOrderFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Application\Bundle\DefaultBundle\Form\Type\PromotionOrderFormType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Promotion controller. For promotions actions
 *
 * @Route("/services/web-design")
 */
class LandingController extends Controller
{
    /**
     * Promotion index page by type
     *
     * @Route("/{type}", requirements={"type" = "mobile-app-design|responsive-design|ui-design|ember-js|silex|sylius"}, name="page_landing")
     * @param string $type
     *
     * @return Response
     */
    public function indexAction($type)
    {
        $form = $this->createForm(new PromotionOrderFormType());

        return $this->render(
            'ApplicationDefaultBundle:Default:landing-' . $type . '.html.twig',
            ['form' => $form->createView()]
        );
    }
}