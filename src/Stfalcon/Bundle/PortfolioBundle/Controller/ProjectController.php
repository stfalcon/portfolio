<?php

namespace Stfalcon\Bundle\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
use Stfalcon\Bundle\PortfolioBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Project controller
 */
class ProjectController extends Controller
{
    /**
     * @param int $page
     *
     * @return array
     * @Route("/portfolio/{page_prefix}/{page}",
     *  name="portfolio_all_projects",
     *  requirements={"page" = "\d+", "page_prefix" = "page"},
     *  defaults={"page"=1, "page_prefix"="page"})
     * @Template("StfalconPortfolioBundle:Project:all_projects.html.twig")
     */
    public function allProjectsAction($page)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('StfalconPortfolioBundle:Project');
        $projectsQuery = $repository->findAllProjectsOrderingByDateAsQuery();
        $projectsWithPaginator = $this->get('knp_paginator')->paginate($projectsQuery, $page, 12);

        return array('projects' => $projectsWithPaginator);
    }

    /**
     * Projects counter widget
     *
     * @return array()
     * @Template("StfalconPortfolioBundle:Project:_projects_counter.html.twig")
     */
    public function projectsCounterAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('StfalconPortfolioBundle:Project');
        $projects = $repository->findAllProjectsOrderingByDate();

        $projectYears = array();
        $projectBefore = 1;
        foreach ($projects as $project) {
            $year = (int) $project->getDate()->format('Y');
            if (isset($projectYears[$year]['counter'])) {
                $projectYears[$year]['counter']++;
            } else {
                $projectYears[$year] = array('year' => $year, 'counter' => $projectBefore);
            }

            $projectBefore ++;
        }

        $projectYears = array_slice($projectYears, -4);

        return array('years' => $projectYears);
    }

    /**
     * View project
     *
     * @param string $categorySlug Slug of category
     * @param string $projectSlug  Slug of project
     *
     * @return array
     *
     * @Route("/portfolio/{categorySlug}/{projectSlug}", name="portfolio_project_view")
     * @Template()
     * @throws NotFoundHttpException
     *
     * @ParamConverter("category", options={"mapping": {"categorySlug": "slug"}})
     * @ParamConverter("project", options={"mapping": {"projectSlug": "slug"}})
     */
    public function viewAction(Request $request, Category $category, Project $project)
    {
        $seo = $this->get('sonata.seo.page');
        $seo->addMeta('name', 'description', $project->getMetaDescription())
            ->addMeta('name', 'keywords', $project->getMetaKeywords())
            ->addMeta('property', 'og:title', $project->getName())
            ->addMeta(
                'property',
                'og:url',
                $this->generateUrl(
                    'portfolio_project_view',
                    [
                        'categorySlug' => $category->getSlug(),
                        'projectSlug' => $project->getSlug()
                    ],
                    true
                )
            )
            ->addMeta('property', 'og:type', 'portfolio')
            ->addMeta('property', 'og:description', $project->getMetaDescription());

        if ($project->getImage()) {
            $seo->addMeta(
                'property',
                'og:image',
                $request->getSchemeAndHttpHost() . $this->get('vich_uploader.templating.helper.uploader_helper')->asset($project, 'imageFile')
            );
        }

        return array('project' => $project, 'category' => $category);
    }

    /**
     * Display links to prev/next projects
     *
     * @param string $categorySlug Object of category
     * @param string $projectSlug  Object of project
     *
     * @return array
     * @Template()
     */
    public function nearbyProjectsAction($categorySlug, $projectSlug)
    {
        // try find category by slug
        $category = $this->_findCategoryBySlug($categorySlug);

        // try find project by slug
        $project = $this->_findProjectBySlug($projectSlug);

        $em = $this->getDoctrine()->getManager();

        // get all projects from this category
        $projects = $em->getRepository("StfalconPortfolioBundle:Project")
                ->getProjectsByCategory($category);

        // get next and previous projects from this category
        $i = 0; $previousProject = null; $nextProject = null;
        foreach ($projects as $p) {
            if ($project->getId() == $p->getId()) {
                $previousProject = isset($projects[$i-1]) ? $projects[$i-1] : null;
                $nextProject     = isset($projects[$i+1]) ? $projects[$i+1] : null;
                break;
            }
            $i++;
        }

        return array('category' => $category, 'previousProject' => $previousProject, 'nextProject' => $nextProject);
    }

    /**
     * Try find category by slug
     *
     * @param string $slug Slug of category
     *
     * @return Category
     * @throws NotFoundHttpException
     */
    private function _findCategoryBySlug($slug)
    {
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository("StfalconPortfolioBundle:Category")
                ->findOneBy(array('slug' => $slug));

        if (!$category) {
            throw new NotFoundHttpException($translator->trans('Категория не существует.'));
        }

        return $category;
    }

    /**
     * Try find project by slug
     *
     * @param string $slug Slug of project
     *
     * @return Project
     * @throws NotFoundHttpException
     */
    private function _findProjectBySlug($slug)
    {
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository("StfalconPortfolioBundle:Project")
                ->findOneBy(array('slug' => $slug));

        if (!$project) {
            throw new NotFoundHttpException($translator->trans('Проект не существует.'));
        }

        return $project;
    }
}
