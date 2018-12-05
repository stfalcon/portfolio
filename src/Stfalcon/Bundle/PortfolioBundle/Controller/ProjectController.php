<?php

namespace Stfalcon\Bundle\PortfolioBundle\Controller;

use Application\Bundle\DefaultBundle\Helpers\SeoOpenGraphEnum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
use Stfalcon\Bundle\PortfolioBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Project controller.
 */
class ProjectController extends Controller
{
    /**
     * @param null $slug
     *
     * @return array
     * @Route("/portfolio/next-projects", name="portfolio_next_projects", options={"expose"=true})
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
            'nextCount' => count($nextPartCategoriesCount),
        ]);
    }

    /**
     * All projects.
     *
     * @param Request     $request Request
     * @param string|null $slug    Project slug
     *
     * @return mixed
     * @Route("/portfolio/{slug}", name="portfolio_category_project")
     * @Route("/portfolio", name="portfolio_all_projects")
     * @Template("StfalconPortfolioBundle:Project:all_projects.html.twig")
     */
    public function allProjectsAction(Request $request, $slug = null)
    {
        $category = null;
        $project = null;
        $nextLimit = 4;
        if ($slug) {
            $category = $this->getDoctrine()
                ->getManager()
                ->getRepository('StfalconPortfolioBundle:Category')
                ->findOneBy(['slug' => $slug]);
            if (!$category) {
                $project = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('StfalconPortfolioBundle:Project')
                    ->findOneBy(['slug' => $slug]);
                if (!$project) {
                    throw $this->createNotFoundException();
                }
            }
        }

        $repository = $this->getDoctrine()->getManager()->getRepository('StfalconPortfolioBundle:Project');
        if ($category) {
            $projects = $repository->findAllExamplesProjectsByCategory($category, 8);
            $nextPartCategoriesCount = $repository->findAllExamplesProjectsByCategory(
                $category,
                $nextLimit,
                count($projects)
            );
        } else {
            $projects = $repository->getAllProjectPortfolio(8);
            $nextPartCategoriesCount = $repository->getAllProjectPortfolio($nextLimit, count($projects));
        }
        $categories = $this->getDoctrine()->getManager()->getRepository('StfalconPortfolioBundle:Category')
            ->getProjectCategories();

        if ($project) {
            $this->setPageSeo($request, $project);

            return $this->render('StfalconPortfolioBundle:Project:view.html.twig',
                [
                    'project' => $project,
                    'category' => $project->getCategories()->first(),
                    'related_projects' => $repository->findRelatedProjectsToCurrentProject($request->getLocale(), $project),
                ]
            );
        } else {
            $this->setPageSeo($request, $category, $slug);

            return [
                'categories' => $categories,
                'active' => $category ? $category->getSlug() : 'all',
                'projects' => $projects,
                'nextCount' => count($nextPartCategoriesCount),
                'itemCount' => count($projects),
                'categorySlug' => $slug,
            ];
        }
    }

    /**
     * View project.
     *
     * @param Request  $request  Request
     * @param Category $category Category
     * @param Project  $project  Project
     *
     * @return RedirectResponse
     *
     * @Route("/portfolio/{categorySlug}/{projectSlug}", name="portfolio_project_view")
     *
     *
     * @ParamConverter("category", options={"mapping": {"categorySlug": "slug"}})
     * @ParamConverter("project", options={"mapping": {"projectSlug": "slug"}})
     */
    public function viewAction(Request $request, Category $category, Project $project)
    {
        return $this->redirectToRoute('portfolio_category_project', ['slug' => $project->getSlug()], 301);
    }

    /**
     * Display links to prev/next projects.
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
        $projects = $em->getRepository('StfalconPortfolioBundle:Project')
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
            ++$i;
        }

        return array('category' => $category, 'previousProject' => $previousProject, 'nextProject' => $nextProject);
    }

    /**
     * Try find category by slug.
     *
     * @param string $slug Slug of category
     *
     * @return Category
     *
     * @throws NotFoundHttpException
     */
    private function _findCategoryBySlug($slug)
    {
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('StfalconPortfolioBundle:Category')
            ->findOneBy(array('slug' => $slug));

        if (!$category) {
            throw new NotFoundHttpException($translator->trans('Категория не существует.'));
        }

        return $category;
    }

    /**
     * Try find project by slug.
     *
     * @param string $slug Slug of project
     *
     * @return Project
     *
     * @throws NotFoundHttpException
     */
    private function _findProjectBySlug($slug)
    {
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('StfalconPortfolioBundle:Project')
            ->findOneBy(array('slug' => $slug));

        if (!$project) {
            throw new NotFoundHttpException($translator->trans('Проект не существует.'));
        }

        return $project;
    }

    /**
     * Widget examples work for page services.
     *
     * @param $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function widgetExamplesProjectAction($category)
    {
        $em = $this->getDoctrine()->getManager();

        $projects = $em->getRepository('StfalconPortfolioBundle:Project')
            ->findAllExamplesProjectsByCategory($category, 4);

        return $this->render('StfalconPortfolioBundle:Category:_widget_examples_prj.html.twig', [
            'projects' => $projects,
            'category' => $category,
        ]);
    }

    /**
     * @param $slug
     *
     * @return Response
     */
    public function widgetExamplesProjectByCtgSlugAction($slug)
    {
        $category = $this->getDoctrine()->getManager()->getRepository('StfalconPortfolioBundle:Category')
            ->findOneBy(['slug' => $slug]);
        if ($category instanceof Category) {
            return $this->widgetExamplesProjectAction($category);
        }

        return null;
    }

    /**
     * @param Request          $request
     * @param Project|Category $entity
     * @param $slug
     */
    private function setPageSeo(Request $request, $entity, $slug = null)
    {
        $seo = $this->get('sonata.seo.page');
        if ($entity instanceof Project || $entity instanceof Category) {
            $canonicalUrl = $this->generateUrl(
                $request->get('_route'),
                [
                    'slug' => $entity->getSlug(),
                ],
                true
            );

            $seo->addMeta('name', 'description', $entity->getMetaDescription())
                ->addMeta('name', 'keywords', $entity->getMetaKeywords())
                ->addMeta('property', 'og:title', $entity->getName())
                ->addMeta('property', 'og:url', $canonicalUrl)
                ->addMeta('property', 'og:type', SeoOpenGraphEnum::ARTICLE)
                ->addMeta('property', 'og:description', $entity->getMetaDescription())
                ->setLinkCanonical($canonicalUrl);

            if ($entity instanceof Project && $entity->getImage()) {
                $vichUploader = $this->get('vich_uploader.templating.helper.uploader_helper');
                $imagePath = $this->get('imagine.cache.path.resolver')
                    ->getBrowserPath(
                        $vichUploader->asset($entity, 'imageFile'),
                        'portfolio_large',
                        true
                    );

                $seo->addMeta(
                    'property',
                    'og:image',
                    $imagePath
                );
            }
        } else {
            $seo->addMeta(
                'property',
                'og:url',
                $this->generateUrl(
                    $request->get('_route'),
                    [
                        'slug' => $slug,
                    ],
                    true
                )
            )
                ->addMeta('property', 'og:type', SeoOpenGraphEnum::WEBSITE);
        }
    }
}
