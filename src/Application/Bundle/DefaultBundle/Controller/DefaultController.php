<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Default controller. For single actions for project
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class DefaultController extends Controller
{
    /**
     * Categories/projects list
     *
     * @return array()
     * @Cache(expires="tomorrow")
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('StfalconPortfolioBundle:Project');
        $projects = $repository->findProjectsForHomePage();

        return array('projects' => $projects);
    }


    /**
     * Contacts page
     *
     * @param Request $request
     *
     * @return array()
     * @Template()
     * @Route("/contacts", name="contacts")
     */
    public function contactsAction(Request $request)
    {
        $seo = $this->get('sonata.seo.page');
        $seo->generateLangAlternates($request);

        // @todo: refact
        if ($this->has('application_default.menu.breadcrumbs')) {
            $breadcrumbs = $this->get('application_default.menu.breadcrumbs');
            $breadcrumbs->addChild('Контакты')->setCurrent(true);
        }

        $directOrderForm = $this->createForm('direct_order', []);

        if ($request->isMethod('post')) {
            $directOrderForm->handleRequest($request);
            if ($directOrderForm->isValid()) {
                $formData = $directOrderForm->getData();
                $container = $this->get('service_container');
                $attachments = [];
                if ($formData['attach']) {
                    /** @var UploadedFile $attach */
                    $attach = $formData['attach'];
                    $attachFile = $attach->move(realpath($container->getParameter('kernel.root_dir') . '/../attachments/'), $attach->getClientOriginalName());
                    $attachments[] = $attachFile;
                }

                $mailer_name = $container->getParameter('mailer_name');
                $mailer_notify = $container->getParameter('mailer_notify');
                $subject = $this->get('translator')->trans('Stfalcon.com direct order');

                $fromEmail = [$formData['email'], $formData['name']];

                if ($this->get('application_default.service.mailer')->sendWithFrom(
                    [$mailer_notify, $mailer_name],
                    $fromEmail,
                    $subject,
                    '@ApplicationDefault/emails/direct_order.html.twig',
                    $formData,
                    $attachments
                    )
                ) {
                    if ($request->isXmlHttpRequest()) {
                        return new JsonResponse([
                            'result'    => 'success',
                            'view'      => $this->renderView('@ApplicationDefault/Default/_direct_order_form_success.html.twig')
                        ]);
                    }

                    $request->getSession()->getFlashBag()->add('success', $this->get('translator')->trans('Спасибо! Мы с Вами свяжемся в ближайшее время.'));

                    return $this->redirect($this->generateUrl('contacts'));
                } else {
                    $request->getSession()->getFlashBag()->add('error', $this->get('translator')->trans('Произошла ошибка при отправке письма.'));
                }

            }
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'result'    => 'error',
                'view'      => $this->renderView('@ApplicationDefault/Default/_direct_order_form.html.twig', ['form' => $directOrderForm->createView()])
            ]);
        }

        return ['form' => $directOrderForm->createView()];
    }

    /**
     * Privacy Policy static page
     *
     * @return array
     *
     * @Template()
     * @Route("/privacy-policy", name="privacy_policy_page")
     */
    public function privacyPolicyAction()
    {
        return [];
    }

    /**
     * Terms of Service static page
     *
     * @return array
     *
     * @Template()
     * @Route("/terms-of-service", name="terms_of_service_page")
     */
    public function termsOfServiceAction()
    {
        return [];
    }
}