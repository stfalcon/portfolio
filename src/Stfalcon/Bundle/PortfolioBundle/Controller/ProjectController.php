<?php

namespace Stfalcon\Bundle\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
use Stfalcon\Bundle\PortfolioBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Project controller
 */
class ProjectController extends Controller
{

    /**
     * @param null $slug
     *
     * @return array
     * @Route("/portfolio/next-projects/", name="portfolio_next_projects", options={"expose"=true})
     * @Route("/portfolio/next-projects/{slug}", name="portfolio_category_next_projects", options={"expose"=true})
     */
    public function getNextProjectsAction($slug = null)
    {
        $request = $this->get('request');
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedException();
        }
        $category = null;
        $limit = $request->get('limit', 4);
        $offset = $request->get('offset', 0);
        $data = [];
        if ($slug) {
            $category = $this->getDoctrine()
                ->getManager()
                ->getRepository('StfalconPortfolioBundle:Category')
                ->findOneBy(['slug' => $slug]);
            if (!$category) {
                $this->createNotFoundException();
            }
        }

        $repository = $this->getDoctrine()->getManager()->getRepository('StfalconPortfolioBundle:Project');
        if ($category) {
            $projects = $repository->findAllExamplesProjectsByCategory($category, $limit, $offset);
            $nextPartCategoriesCount = $repository->findAllExamplesProjectsByCategory($category, $limit,
                count($projects) + $offset);
        } else {
            $projects = $repository->getAllProjectPortfolio($limit, $offset);
            $nextPartCategoriesCount = $repository->getAllProjectPortfolio($limit, count($projects) + $offset);
        }

        foreach ($projects as $project) {
            $data[] = $this->renderView('@StfalconPortfolio/Project/_project_load_item.html.twig',
                ['project' => $project]);
        }


        return new JsonResponse([
            'data' => $data,
            'nextCount' => count($nextPartCategoriesCount)
        ]);
    }

    /**
     * @param int $page
     *
     * @return array
     * @Route("/portfolio/{slug}/", name="portfolio_category_project")
     * @Route("/portfolio/", name="portfolio_all_projects")
     * @Template("StfalconPortfolioBundle:Project:all_projects.html.twig")
     */
    public function allProjectsAction($slug = null)
    {
        $request = $this->get('request');
        $seo = $this->get('sonata.seo.page');
        $seo->generateLangAlternates($request);
        $category = null;
        $nextLimit = 4;
        if ($slug) {
            $category = $this->getDoctrine()
                ->getManager()
                ->getRepository('StfalconPortfolioBundle:Category')
                ->findOneBy(['slug' => $slug]);
            if (!$category) {
                throw $this->createNotFoundException();
            }
        }

        $repository = $this->getDoctrine()->getManager()->getRepository('StfalconPortfolioBundle:Project');
        if ($category) {
            $projects = $repository->findAllExamplesProjectsByCategory($category, 7);
            $nextPartCategoriesCount = $repository->findAllExamplesProjectsByCategory($category, $nextLimit,
                count($projects) + $nextLimit);
        } else {
            $projects = $repository->getAllProjectPortfolio();
            $nextPartCategoriesCount = $repository->getAllProjectPortfolio($nextLimit, count($projects) + $nextLimit);
        }
        $categories = $this->getDoctrine()->getManager()->getRepository('StfalconPortfolioBundle:Category')->findAll();

        return array(
            'categories' => $categories,
            'active' => $category ? $category->getSlug() : 'all',
            'projects' => $projects,
            'nextCount' => count($nextPartCategoriesCount),
            'itemCount' => count($projects),
            'categorySlug' => $slug,
        );
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

            $projectBefore++;
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
        $categorySlug = $project->getCategories()->first()->getSlug();
        $canonicalUrl = $this->generateUrl(
            'portfolio_project_view',
            [
                'categorySlug' => $categorySlug,
                'projectSlug' => $project->getSlug()
            ],
            true
        );

        $seo = $this->get('sonata.seo.page');

        $seo->addMeta('name', 'description', $project->getMetaDescription())
            ->addMeta('name', 'keywords', $project->getMetaKeywords())
            ->addMeta('property', 'og:title', $project->getName())
            ->addMeta('property', 'og:url', $canonicalUrl)
            ->addMeta('property', 'og:type', 'portfolio')
            ->addMeta('property', 'og:description', $project->getMetaDescription())
            ->setLinkCanonical($canonicalUrl);

        $seo->generateLangAlternates($this->get('request'));

        if ($project->getImage()) {
            $seo->addMeta(
                'property',
                'og:image',
                $request->getSchemeAndHttpHost() . $this->get('vich_uploader.templating.helper.uploader_helper')->asset($project,
                    'imageFile')
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
        $i = 0;
        $previousProject = null;
        $nextProject = null;
        foreach ($projects as $p) {
            if ($project->getId() == $p->getId()) {
                $previousProject = isset($projects[$i - 1]) ? $projects[$i - 1] : null;
                $nextProject = isset($projects[$i + 1]) ? $projects[$i + 1] : null;
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

    /**
     * Widget examples work for page services
     *
     * @param $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function widgetExamplesProjectAction($category)
    {
        $em = $this->getDoctrine()->getManager();

        $projects = $em->getRepository("StfalconPortfolioBundle:Project")
            ->findAllExamplesProjectsByCategory($category, 4);

        return $this->render('StfalconPortfolioBundle:Category:_widget_examples_prj.html.twig', [
            'projects' => $projects,
            'category' => $category
        ]);
    }
}
