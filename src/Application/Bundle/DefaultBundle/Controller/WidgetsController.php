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
                $this->get('hype_mailchimp')
                     ->getList()
                     ->addMerge_vars([
                         'locale' => $request->getLocale(),
                     ])
                     ->subscribe($form->get('email')->getData(), 'html', false);

                return new JsonResponse([
                    'success' => true,
                    'message' => $this->get('translator')->trans('Мы записали ваш адрес. Обещаем не спамить.'),
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
     * @param $slug
     * @return Response
     *
     * @Route(name="hire_us")
     */
    public function hireUsAction($slug = null)
    {
        $form = $this->createForm(new PromotionOrderFormType());

        return $this->render('ApplicationDefaultBundle:Widgets:_hire_us_form.html.twig', [
            'form' => $form->createView(),
            'slug' => $slug,
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
            $mailerNotify = $container->getParameter('mailer_notify');
            $subject      = $translated->trans('promotion.order.hire.us.mail.subject', ['%email%' => $email]);

            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($mailerNotify)
                ->setReplyTo($email)
                ->setTo($mailerNotify)
                ->setBody(
                    $this->renderView(
                        '@ApplicationDefault/emails/order_app.html.twig',
                        [
                            'message' => $data['message'],
                            'name'    => $name,
                            'email'   => $email,
                        ]
                    ),
                    'text/html'
                );
            $resultSending = $this->get('mailer')->send($message);

//            if ($resultSending) {
                return new JsonResponse([
                    'status' => 'success',
                ]);
//            }
        }

        return new JsonResponse([
            'status' => 'error',
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
        if (isset($routeParams['page'])) {
            unset($routeParams['page']);
        }

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
