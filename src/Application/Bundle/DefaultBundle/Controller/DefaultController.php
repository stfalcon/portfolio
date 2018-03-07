<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Application\Bundle\DefaultBundle\Entity\SeoHomepage;
use Application\Bundle\DefaultBundle\Helpers\SeoOpenGraphEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Default controller. For single actions for project.
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class DefaultController extends Controller
{
    /**
     * Categories/projects list.
     *
     * @param Request $request Request
     *
     * @return array()
     *
     * @Cache(expires="tomorrow")
     *
     * @Route("/{_locale}", name="homepage", defaults={"_locale": "en"}, requirements={"_locale": "en|ru"}, options={"i18n"=false})
     *
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $seo = $this->get('sonata.seo.page');
        $seo
            ->addMeta('property', 'og:url', $this->generateUrl($request->get('_route'), [], true))
            ->addMeta('property', 'og:type', SeoOpenGraphEnum::WEBSITE);

        $seoHomepage = $this->getDoctrine()->getRepository('ApplicationDefaultBundle:SeoHomepage')->findOneBy([]);
        if ($seoHomepage instanceof SeoHomepage) {
            $seo
                ->setTitle($seoHomepage->getTitle())
                ->addMeta('name', 'keywords', $seoHomepage->getKeywords())
                ->addMeta('name', 'description', $seoHomepage->getDescription())
                ->addMeta('property', 'og:title', $seoHomepage->getOgTitle())
                ->addMeta('property', 'og:description', $seoHomepage->getDescription())
                ->addMeta('property', 'og:image', '/img/'.$seoHomepage->getOgImage());
        }

        return [];
    }

    /**
     * index-new page.
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @Route("/index-new", name="index-new", defaults={"_locale": "en"})
     */
    public function indexNewAction(Request $request)
    {
        $services = $this->getDoctrine()->getRepository('StfalconPortfolioBundle:Category')
            ->findBy(['showInServices' => true], ['ordernum' => 'ASC']);
        $locale = $request->getLocale() ? $request->getLocale() : 'en';
        $posts = $this->get('doctrine')->getManager()
            ->getRepository('StfalconBlogBundle:Post')->getLastPosts($locale, 3);

        $projects = $this->getDoctrine()->getRepository('StfalconPortfolioBundle:Project')
            ->findBy(['onFrontPage' => true], ['orderNumber' => 'ASC']);

        $activeReviews = $this->getDoctrine()->getRepository('StfalconPortfolioBundle:ProjectReview')
            ->getActiveReviews($projects);

        $seo = $this->get('sonata.seo.page');
        $seo
            ->addMeta('property', 'og:url', $this->generateUrl($request->get('_route'), [], true))
            ->addMeta('property', 'og:type', SeoOpenGraphEnum::WEBSITE);

        $seoHomepage = $this->getDoctrine()->getRepository('ApplicationDefaultBundle:SeoHomepage')->findOneBy([]);
        if ($seoHomepage instanceof SeoHomepage) {
            $seo
                ->setTitle($seoHomepage->getTitle())
                ->addMeta('name', 'keywords', $seoHomepage->getKeywords())
                ->addMeta('name', 'description', $seoHomepage->getDescription())
                ->addMeta('property', 'og:title', $seoHomepage->getOgTitle())
                ->addMeta('property', 'og:description', $seoHomepage->getDescription())
                ->addMeta('property', 'og:image', '/img/'.$seoHomepage->getOgImage());
        }

        return $this->render(
            '@ApplicationDefault/Default/index-new.html.twig',
            [
                'services' => $services,
                'posts' => $posts,
                'reviews' => $activeReviews,
            ]
        );
    }

    /**
     * Contacts page.
     *
     * @param Request $request Request
     *
     * @return array()
     * @Template()
     * @Route("/contacts", name="contacts")
     */
    public function contactsAction(Request $request)
    {
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
                    $attachFile = $attach->move(realpath($container->getParameter('kernel.root_dir').'/../attachments/'), $attach->getClientOriginalName());
                    $attachments[] = $attachFile;
                }

                $subject = $this->get('translator')->trans('Stfalcon.com direct order from "%email%"', ['%email%' => $formData['email']]);
                $mailerNotify = $container->getParameter('mailer_notify');

                $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom($mailerNotify)
                    ->setReplyTo($formData['email'])
                    ->setTo($mailerNotify);

                foreach ($attachments as $file) {
                    $message->attach(\Swift_Attachment::fromPath($file->getRealPath())->setFilename($file->getFilename()));
                }

                $message->setBody(
                    $this->renderView(
                        '@ApplicationDefault/emails/direct_order.html.twig',
                        $formData
                    ),
                    'text/html'
                );
                $resultSending = $this->get('mailer')->send($message);
                if ($resultSending) {
                    if ($request->isXmlHttpRequest()) {
                        return new JsonResponse([
                            'result' => 'success',
                            'view' => $this->renderView('@ApplicationDefault/Default/_direct_order_form_success.html.twig'),
                        ]);
                    }

                    $request->getSession()->getFlashBag()->add('success', $this->get('translator')->trans('Спасибо! Мы с Вами свяжемся в ближайшее время.'));

                    return $this->redirect($this->generateUrl('contacts'));
                } else {
                    $request->getSession()->getFlashBag()->add('error', $this->get('translator')->trans('Произошла ошибка при отправке письма.'));
                }
            }
        }

        $seo = $this->get('sonata.seo.page');
        $seo->addMeta('property', 'og:url', $this->generateUrl($request->get('_route'), [], true))
            ->addMeta('property', 'og:type', SeoOpenGraphEnum::WEBSITE);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'result' => 'error',
                'view' => $this->renderView('@ApplicationDefault/Default/_direct_order_form.html.twig', ['form' => $directOrderForm->createView()]),
            ]);
        }

        return ['form' => $directOrderForm->createView()];
    }

    /**
     * Privacy Policy static page.
     *
     * @param Request $request Request
     *
     * @return array
     *
     * @Template()
     * @Route("/privacy-policy", name="privacy_policy_page")
     */
    public function privacyPolicyAction(Request $request)
    {
        $seo = $this->get('sonata.seo.page');
        $seo->addMeta('property', 'og:url', $this->generateUrl($request->get('_route'), [], true))
            ->addMeta('property', 'og:type', SeoOpenGraphEnum::WEBSITE);

        return [];
    }

    /**
     * Terms of Service static page.
     *
     * @param Request $request Request
     *
     * @return array
     *
     * @Template()
     * @Route("/terms-of-service", name="terms_of_service_page")
     */
    public function termsOfServiceAction(Request $request)
    {
        $seo = $this->get('sonata.seo.page');
        $seo->addMeta('property', 'og:url', $this->generateUrl($request->get('_route'), [], true))
            ->addMeta('property', 'og:type', SeoOpenGraphEnum::WEBSITE);

        return [];
    }
}
