<?php

namespace Stfalcon\Bundle\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
use Stfalcon\Bundle\PortfolioBundle\Entity\Category;

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
     */
    public function viewAction($categorySlug, $projectSlug)
    {
        // @todo упростить когда что-то разрулят с этим PR https://github.com/sensio/SensioFrameworkExtraBundle/pull/42

        // try find category by slug
        $category = $this->_findCategoryBySlug($categorySlug);

        // try find project by slug
        $project = $this->_findProjectBySlug($projectSlug);

        if (!$project->getCategories()->contains($category)) {
            throw new NotFoundHttpException('The project does not exist.');
        }

        if ($this->has('application_default.menu.breadcrumbs')) {
            $breadcrumbs = $this->get('application_default.menu.breadcrumbs');
            $breadcrumbs->addChild(
                $category->getName(),
                array(
                    'route' => 'portfolio_category_view',
                    'routeParameters' => array('slug' => $category->getSlug())
                )
            );
            $breadcrumbs->addChild($project->getName())->setCurrent(true);
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

        $em = $this->get('doctrine')->getEntityManager();

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
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository("StfalconPortfolioBundle:Category")
                ->findOneBy(array('slug' => $slug));

        if (!$category) {
            throw new NotFoundHttpException('The category does not exist.');
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
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository("StfalconPortfolioBundle:Project")
                ->findOneBy(array('slug' => $slug));

        if (!$project) {
            throw new NotFoundHttpException('The project does not exist.');
        }

        return $project;
    }
}
