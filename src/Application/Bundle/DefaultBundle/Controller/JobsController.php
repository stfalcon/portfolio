<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Application\Bundle\DefaultBundle\Helpers\SeoOpenGraphEnum;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\Bundle\DefaultBundle\Entity\Jobs;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class JobsController extends Controller
{
    /**
     * List of Jobs.
     *
     * @param Request $request Request
     * @param int     $page    Page number
     *
     * @return array|RedirectResponse
     *
     * @Route("/jobs/{title}/{page}", name="jobs_list",
     *      requirements={"page"="\d+", "title"="page"},
     *      defaults={"page"="1", "title"="page"})
     * @Template()
     */
    public function indexAction(Request $request, $page)
    {
        if ('ru' !== $request->getLocale()) {
            return $this->redirectToRoute('jobs_list', ['page' => $page, '_locale' => 'ru']);
        }

        $itemsPerPage = 10;
        $jobsRepository = $this->getDoctrine()->getRepository('ApplicationDefaultBundle:Jobs');

        $jobsQuery = $jobsRepository->findBy(['active' => true]);
        $cnt = count($jobsQuery);
        $maxPages = intdiv($cnt, $itemsPerPage);
        if ($cnt / $itemsPerPage > intdiv($cnt, $itemsPerPage)) {
            ++$maxPages;
        }
        $page = $page > $maxPages ? $maxPages : $page;
        $jobs = $this->get('knp_paginator')->paginate($jobsQuery, $page, $itemsPerPage);

        $seo = $this->get('sonata.seo.page');
        $seo->addMeta('property', 'og:url', $this->generateUrl($request->get('_route'), [], true))
            ->addMeta('property', 'og:type', SeoOpenGraphEnum::WEBSITE);

        return [
            'jobs' => $jobs,
        ];
    }

    /**
     * View job.
     *
     * @param Request $request
     * @param string  $slug
     *
     * @return array| RedirectResponse
     *
     * @throws NotFoundHttpException
     *
     * @Route("/jobs/job/{slug}", name="jobs_job_view")
     *
     * @Template()
     */
    public function viewAction(Request $request, $slug)
    {
        if ('ru' !== $request->getLocale()) {
            return $this->redirectToRoute('jobs_job_view', ['slug' => $slug, '_locale' => 'ru']);
        }

        /** @var Jobs $job */
        $job = $this->getDoctrine()
            ->getRepository('ApplicationDefaultBundle:Jobs')
            ->findJobBySlug($slug);

        if (!$job) {
            return $this->redirect($this->generateUrl('jobs_list'));
        }
        $vacancyForm = $this->createForm('vacancy_form', []);

        if ($request->isMethod('post')) {
            $vacancyForm->handleRequest($request);
            if ($vacancyForm->isValid()) {
                $formData = $vacancyForm->getData();
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
                        '@ApplicationDefault/emails/vacancy.html.twig',
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
        $seo->addMeta('name', 'description', $job->getMetaDescription())
            ->addMeta('name', 'keywords', $job->getMetaKeywords())
            ->addMeta('property', 'og:title', $job->getMetaTitle())
            ->addMeta('property', 'og:url', $this->generateUrl($request->get('_route'), [
                'slug' => $job->getSlug(),
            ], true))
            ->addMeta('property', 'og:type', SeoOpenGraphEnum::ARTICLE)
            ->addMeta('property', 'og:description', $job->getMetaDescription());

        $this->get('app.default.seo_alternate')->addAlternate($job, $seo, $request);

        return [
            'job' => $job,
            'form' => $vacancyForm->createView(),
        ];
    }
}
