<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Application\Bundle\DefaultBundle\Form\Type\VacancyFormType;
use Application\Bundle\DefaultBundle\Helpers\SeoOpenGraphEnum;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Application\Bundle\DefaultBundle\Entity\Job;
use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class JobsController extends Controller
{
    /**
     * List of Jobs.
     *
     * @param Request $request Request
     * @param int     $page    Page number
     *
     * @return Response
     *
     * @Route("/jobs/{title}/{page}", name="jobs_list",
     *      requirements={"page"="\d+", "title"="page"},
     *      defaults={"page"="1", "title"="page"})
     */
    public function indexAction(Request $request, $page)
    {
        if ('ru' !== $request->getLocale()) {
            return $this->redirectToRoute('jobs_list', ['page' => $page, '_locale' => 'ru']);
        }

        $itemsPerPage = 10;
        $jobsRepository = $this->getDoctrine()->getRepository('ApplicationDefaultBundle:Job');

        $jobsQuery = $jobsRepository->findBy(['active' => true], ['sortOrder' => 'DESC']);
        $jobsCount = count($jobsQuery);
        $maxPages = intdiv($jobsCount, $itemsPerPage);
        if ($jobsCount / $itemsPerPage > intdiv($jobsCount, $itemsPerPage)) {
            ++$maxPages;
        }
        $page = $page > $maxPages ? $maxPages : $page;
        $jobs = $this->get('knp_paginator')->paginate($jobsQuery, $page, $itemsPerPage);

        $seo = $this->get('sonata.seo.page');
        $seo->addMeta('property', 'og:url', $this->generateUrl($request->get('_route'), [], true))
            ->addMeta('property', 'og:type', SeoOpenGraphEnum::WEBSITE);

        return $this->render('@ApplicationDefault/Jobs/index.html.twig', ['jobs' => $jobs]);
    }

    /**
     * View job.
     *
     * @param Request $request
     * @param string  $slug
     *
     * @return Response|JsonResponse
     *
     * @throws NotFoundHttpException
     *
     * @Route("/jobs/job/{slug}", name="jobs_job_view")
     */
    public function viewAction(Request $request, $slug)
    {
        if ('ru' !== $request->getLocale()) {
            return $this->redirectToRoute('jobs_job_view', ['slug' => $slug, '_locale' => 'ru']);
        }

        /** @var Job $job */
        $job = $this->getDoctrine()
            ->getRepository('ApplicationDefaultBundle:Job')
            ->findJobBySlug($slug);

        if (!$job) {
            return $this->redirect($this->generateUrl('jobs_list'));
        }
        $vacancyForm = $this->createForm('vacancy_form');
        $vacancyForm->handleRequest($request);
        if ($vacancyForm->isSubmitted() && $vacancyForm->isValid()) {
            $formData = $vacancyForm->getData();

            $resultSending = $this->get('application_default.service.mailer')->sendCandidateProfile($formData, $job->getTitle());
            if ($resultSending) {
                $request->getSession()->getFlashBag()->add('vacancy_send', $this->get('translator')->trans('Спасибо! Мы с Вами свяжемся в ближайшее время.'));

                return $this->redirect($this->generateUrl('jobs_job_view', ['slug' => $slug]));
            }
            $request->getSession()->getFlashBag()->add('vacancy_error', $this->get('translator')->trans('Произошла ошибка при отправке письма.'));
        }
        $seo = $this->getSeo($job, $request);
        $this->get('app.default.seo_alternate')->addAlternate($job, $seo, $request);

        return $this->render(
            '@ApplicationDefault/Jobs/view.html.twig',
            [
                'job' => $job,
                'form' => $vacancyForm->createView(),
            ]
        );
    }

    /**
     * @param Job     $job
     * @param Request $request
     *
     * @return SeoPage
     */
    private function getSeo(Job $job, Request $request)
    {
        $seo = $this->get('sonata.seo.page');
        $seo->addMeta('name', 'description', $job->getMetaDescription())
            ->addMeta('name', 'keywords', $job->getMetaKeywords())
            ->addMeta('property', 'og:title', $job->getMetaTitle())
            ->addMeta('property', 'og:url', $this->generateUrl($request->get('_route'), [
                'slug' => $job->getSlug(),
            ], true))
            ->addMeta('property', 'og:type', SeoOpenGraphEnum::ARTICLE)
            ->addMeta('property', 'og:description', $job->getMetaDescription());

        return $seo;
    }
}
