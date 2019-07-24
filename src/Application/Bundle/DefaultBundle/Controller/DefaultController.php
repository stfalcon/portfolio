<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Application\Bundle\DefaultBundle\Entity\SeoHomepage;
use Application\Bundle\DefaultBundle\Helpers\SeoOpenGraphEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

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
                ->addMeta('name', 'keywords', $seoHomepage->getKeywords())
                ->addMeta('name', 'description', $seoHomepage->getDescription())
                ->addMeta('property', 'og:title', $seoHomepage->getOgTitle())
                ->addMeta('property', 'og:description', $seoHomepage->getDescription())
                ->addMeta('property', 'og:image', $request->getSchemeAndHttpHost().'/img/'.$seoHomepage->getOgImage());
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
     * @return Response
     *
     * @Route("/calculator", name="calculator")
     */
    public function calculatorAction()
    {
        $title = $this->get('translator')->trans('__calculator.title');
        $seo = $this->get('sonata.seo.page');
        $seo
            ->addMeta('property', 'og:title', $title)
            ->addMeta('property', 'og:description', $this->get('translator')->trans('__calculator.description'))
        ;

        return $this->render('@ApplicationDefault/Default/calculator-page.html.twig', ['main_title' => $title]);
    }

    /**
     * Opensource page.
     *
     * @param Request $request Request
     *
     * @return array()
     * @Template()
     * @Route("/opensource", name="opensource")
     */
    public function opensourceAction(Request $request)
    {
        return [];
    }

    /**
     * @Route(path="/get-projects-stars", name="get_projects_stars",
     *     options = {"expose"=true},
     *     condition="request.isXmlHttpRequest()")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getProjectsStars(Request $request)
    {
        $projects = $request->get('names');
        if (0 === count($projects)) {
            return new JsonResponse(['error' => true]);
        }
        $resultProjects = [];
        foreach ($projects as $key => $project) {
            if (isset($project['repoName']) && isset($project['user'])) {
                $resultProjects[$project['repoName']] = $this->get('app.service.git_hub_api_service')->getProjectStarsCountByName($project);
            }
        }

        return new JsonResponse(['data' => $resultProjects]);
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
                $subject = $this->get('translator')->trans('Stfalcon.com direct order from "%email%"', ['%email%' => $formData['email']]);
                $mailerNotify = $container->getParameter('mailer_notify');
                $formData['country'] = $this->get('application_default.service.geo_ip')->getCountryByIp($request->getClientIp());

                $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom($mailerNotify)
                    ->setReplyTo($formData['email'])
                    ->setTo($mailerNotify);

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

    /**
     * @param Request $request
     *
     * @Route("/order", name="send_order", methods={"POST"})
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function sendOrderByPriceAction(Request $request)
    {
        $json = $request->getContent();
        $content = \json_decode($json, true);

        if (!isset($content['order'], $content['email'], $content['platform']) || 0 === \count($content['order'])) {
            return new JsonResponse('Bad request!', JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!in_array($content['platform'], ['android', 'ios', 'android_ios'], true)) {
            return new JsonResponse('Bad request platform!', JsonResponse::HTTP_BAD_REQUEST);
        }
        $emailConstraint =
            [
                new NotBlank(),
                new Email(['strict' => true]),
            ];
        /** @var $errors \Symfony\Component\Validator\ConstraintViolationList */
        $errors = $this->get('validator')->validate($content['email'], $emailConstraint);
        if ($errors->count() > 0) {
            return new JsonResponse('Bad request email!', JsonResponse::HTTP_BAD_REQUEST);
        }

        $email = $content['email'];
        try {
            $content = $this->get('app.price.service')->preparePrice($content);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse('Bad request!', JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse('Something wrong!', JsonResponse::HTTP_NOT_FOUND);
        }

        $pdfService = $this->get('app.helper.new_pdf_generator');
        $pdf = $pdfService->generatePdfFile($email, $content);
        $country = $this->get('application_default.service.geo_ip')->getCountryByIp($request->getClientIp());
        $this->get('application_default.service.mailer')->sendOrderPdf($email, $pdf);
        $this->get('application_default.service.mailer')->sendOrderPdfToStfalcon($email, $pdf, $country);

        return new JsonResponse();
    }
}
