<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Application\Bundle\DefaultBundle\Form\Type\PromotionOrderFormType;
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

    /**
     * Promotions apps page
     *
     * @Route("/promotion/apps", name="page_promotion_apps")
     */
    public function promotionAppsAction()
    {
        $form = $this->createForm(new PromotionOrderFormType());

        return $this->render(
            '@ApplicationDefault/Default/promotion_apps.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/order/apps", name="order_apps")
     */
    public function orderAppsAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Not supported');
        }

        $form = $this->createForm(new PromotionOrderFormType());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];
            $name  = $data['name'];


            // Get base template for email
            $templateContent = $this->get('twig')->loadTemplate(
                'ApplicationDefaultBundle:emails:order_app.html.twig'
            );

            $body = $templateContent->render(
                [
                    'message' => $data['message'],
                    'name'    => $name,
                    'email'   => $email
                ]
            );

            $mailer_from = $this->get('service_container')->getParameter('mailer_from');
            $mailer_name = $this->get('service_container')->getParameter('mailer_name');
            $mailer_notify = $this->get('service_container')->getParameter('mailer_notify');

            $message = \Swift_Message::newInstance()
                ->setSubject('Заявка на разработку мобильного приложения от "' . $email . '"')
                ->setFrom($email, $name)
                ->setTo($mailer_notify, $mailer_name)
                ->setBody($body, 'text/html');

            $mailer = $this->get('mailer');
            if ($mailer->send($message)) {
                return new JsonResponse(['status' => 'success']);
            } else {
                return new JsonResponse(['status' => 'error']);
            }
        } else {
            return new JsonResponse(['status' => 'error']);
        }
    }
}