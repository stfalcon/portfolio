<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Application\Bundle\DefaultBundle\Form\Type\PromotionOrderFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Widgets controller
 *
 */
class WidgetsController extends Controller
{
    /**
     * @param Request $request
     *
     * @Template("ApplicationDefaultBundle:Widgets:_language_switcher.html.twig")
     * @return array
     */
    public function languageSwitcherAction($request)
    {
        $locales = array(
//            'de' => array(
//                'link' => $this->localizeRoute($request, 'de'),
//                'lang' => 'DE'
//            ),
            'ru' => array(
                'link' => $this->localizeRoute($request, 'ru'),
                'lang' => 'RU'
            ),
            'en' => array(
                'link' => $this->localizeRoute($request, 'en'),
                'lang' => 'EN'
            ),
        );

        return array('locales' => $locales);
    }

    /**
     * Subscribe widget action
     *
     * @param Request $request  Request
     * @param string  $category Category
     *
     * @return array
     *
     * @Template("ApplicationDefaultBundle:Widgets:_subscribe_form.html.twig")
     * @Route(
     *      "/subscribe/{category}",
     *      requirements={"category" = "blog"},
     *      name="post_subscribe"
     * )
     */
    public function subscribeWidgetAction(Request $request, $category)
    {
        $form = $this->createForm('subscribe');

        if ($request->isMethod('post') && $request->isXmlHttpRequest()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $mc = $this->get('hype_mailchimp');
                $mc->getList()->subscribe($form->get('email')->getData(), 'html', false);

                return new JsonResponse([
                    'success' => true
                ]);
            }

            return new JsonResponse([
                'success' => false,
                'view' => $this->renderView('ApplicationDefaultBundle:Widgets:_subscribe_form.html.twig', [
                    'form' => $form->createView(),
                    'category' => $category
                ])
            ]);
        }

        return [
            'form' => $form->createView(),
            'category' => $category
        ];
    }

    /**
     * Hire us action
     *
     * @return Response
     *
     * @Route(name="hire_us")
     */
    public function hireUsAction()
    {
        $form = $this->createForm(new PromotionOrderFormType());

        return $this->render('ApplicationDefaultBundle:Widgets:_hire_us_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Hire us action
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throw NotFoundHttpException
     *
     * @Route("/ajax-hire-us", name="ajax_hire_us")
     */
    public function hireUsAjaxAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(new PromotionOrderFormType());
        $form->handleRequest($request);
        $translated = $this->get('translator');

        if ($form->isValid() && $request->isMethod('post')) {
            $data  = $form->getData();
            $email = $data['email'];
            $name  = $data['name'];

            $container    = $this->get('service_container');
            $mailerName   = $container->getParameter('mailer_name');
            $mailerNotify = $container->getParameter('mailer_notify');
            $subject      = $translated->trans('promotion.order.hire.us.mail.subject', ['%email%' => $email]);

            $resultSending = $this->get('application_default.service.mailer')->send(
                [$mailerNotify, $mailerName],
                $subject,
                '@ApplicationDefault/emails/order_app.html.twig',
                [
                    'message' => $data['message'],
                    'name'    => $name,
                    'email'   => $email,
                ]
            );

            if ($resultSending) {
                return new JsonResponse([
                    'status' => 'success'
                ]);
            }
        }

        return new JsonResponse([
            'status' => 'error'
        ]);
    }

    /**
     * Localize current route
     *
     * @param Request $request
     * @param string  $locale
     *
     * @return string
     */
    protected function localizeRoute($request, $locale)
    {
        $routeParams = $request->attributes->get('_route_params');
        $attributes = $request->query->all();
        if (!is_null($routeParams)) {
            $attributes = array_merge($attributes, $routeParams);
        }

        // Set/override locale
        $attributes['_locale'] = $locale;

        $route = $request->attributes->get('_route');
        if (is_null($route)) {
            $route = 'homepage';
        }

        return $this->generateUrl($route, $attributes);
    }
}
