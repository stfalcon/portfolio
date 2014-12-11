<?php
namespace Application\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Application\Bundle\DefaultBundle\Form\Type\PromotionOrderFormType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Promotion controller. For promotions actions
 *
 * @Route("/promotion")
 */
class PromotionController extends Controller
{
    /**
     * Promotions apps page
     *
     * @Route("/apps", name="page_promotion_apps")
     */
    public function appsAction()
    {
        $form = $this->createForm(new PromotionOrderFormType());

        return $this->render(
            'ApplicationDefaultBundle:Default:promotion_apps.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/order-apps", name="order_apps")
     */
    public function orderAppsAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Not supported');
        }

        $form = $this->createForm(new PromotionOrderFormType());
        $form->handleRequest($request);
        $translated = $this->get('translator');

        if ($form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];
            $name  = $data['name'];

            $container = $this->get('service_container');
            $mailer_name = $container->getParameter('mailer_name');
            $mailer_notify = $container->getParameter('mailer_notify');
            $subject = $translated->trans('Заявка на разработку мобильного приложения от "%email%"', ['%email%' => $email]);

            if ($this->get('application_default.service.mailer')->send(
                [$mailer_notify, $mailer_name],
                $subject,
                '@ApplicationDefault/emails/order_app.html.twig',
                [
                    'message' => $data['message'],
                    'name'    => $name,
                    'email'   => $email
                ]
            )) {
                return new JsonResponse(['status' => 'success']);
            } else {
                return new JsonResponse(['status' => 'error']);
            }
        } else {
            return new JsonResponse(['status' => 'error']);
        }
    }

    /**
     * Promotions design page
     *
     * @Route("/design", name="page_promotion_design")
     */
    public function designAction()
    {
        $form = $this->createForm(new PromotionOrderFormType());

        return $this->render(
            'ApplicationDefaultBundle:Default:promotion_design.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/order-design", name="order_design")
     */
    public function orderDesignAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Not supported');
        }

        $form = $this->createForm(new PromotionOrderFormType());
        $form->handleRequest($request);
        $translated = $this->get('translator');

        if ($form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];
            $name  = $data['name'];

            $container = $this->get('service_container');
            $mailer_name = $container->getParameter('mailer_name');
            $mailer_notify = $container->getParameter('mailer_notify');
            $subject = $translated->trans('Заявка на разработку дизайна от "%email%"', ['%email%' => $email]);

            if ($this->get('application_default.service.mailer')->send(
                [$mailer_notify, $mailer_name],
                $subject,
                '@ApplicationDefault/emails/order_app.html.twig',
                [
                    'message' => $data['message'],
                    'name'    => $name,
                    'email'   => $email
                ]
            )) {
                return new JsonResponse(['status' => 'success']);
            } else {
                return new JsonResponse(['status' => 'error']);
            }
        } else {
            return new JsonResponse(['status' => 'error']);
        }
    }
}