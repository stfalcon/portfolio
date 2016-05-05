<?php

namespace Stfalcon\Bundle\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
use Stfalcon\Bundle\PortfolioBundle\Entity\Category;

/**
 * Category Controller
 */
class CategoryController extends Controller
{
    /**
     * List of categories
     *
     * @param Request  $request  Request
     * @param Category $category Category
     *
     * @return array
     *
     * @Route("/services/{slug}", name="portfolio_categories_list")
     * @ParamConverter("category", class="StfalconPortfolioBundle:Category", options={"mapping": {"slug": "slug"}})
     * @Template()
     */
    public function servicesAction(Request $request, Category $category)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('StfalconPortfolioBundle:Category');
        $categories = $repository->getServicesCategories();

        $linkTexts = array(
            'web-development'    => 'веб-разработки',
            'web-design'         => 'разработки дизайна',
            'mobile-development' => 'разработки мобильных приложений',
            'game-development'   => 'создания игр',
            'consulting-audit'   => 'консалтинга и аудита'
        );

        $seo = $this->get('sonata.seo.page');
        $seo->addMeta('name', 'description', $category->getMetaDescription())
            ->addMeta('name', 'keywords', $category->getMetaKeywords())
            ->addMeta(
                'property',
                'og:url',
                $this->generateUrl(
                    'portfolio_categories_list',
                    [
                        'slug' => $category->getSlug(),
                    ],
                    true
                )
            )
            ->addMeta('property', 'og:title', $category->getTitle())
            ->addMeta('property', 'og:description', $category->getMetaDescription());

        $this->get('app.default.seo_alternate')->addAlternateForCategory($category, $seo, $request);

        return array(
            'category'   => $category,
            'categories' => $categories,
            'linkTexts'  => $linkTexts
        );
    }

    /**
     * View category
     *
     * @param Category $category Category object
     * @param int      $page     Page number
     *
     * @return array
     * @Route(
     *      "/portfolio/{slug}/{page_prefix}/{page}",
     *      name="portfolio_category_view",
     *      requirements={"page" = "\d+", "page_prefix" = "page"},
     *      defaults={"page_prefix" = "page", "page" = "1"}
     * )
     * @Template()
     */
    public function viewAction(Category $category, $page = 1)
    {
        $query = $this->getDoctrine()
            ->getRepository("StfalconPortfolioBundle:Project")
            ->getQueryForSelectProjectsByCategory($category, 'p.ordernum', 'ASC');

        $paginatedProjects = $this->get('knp_paginator')->paginate($query, $page, 12);
        $paginatedProjects->setUsedRoute('portfolio_category_view');

        if ($this->has('application_default.menu.breadcrumbs')) {
            $breadcrumbs = $this->get('application_default.menu.breadcrumbs');
            $breadcrumbs->addChild($category->getName())->setCurrent(true);
        }

        return array(
            'category' => $category,
            'projects' => $paginatedProjects
        );
    }

    /**
     * Ajax order projects
     *
     * @return string
     * @Route("/admin/portfolio/category/applyOrder", name="portfolioProjectsApplyOrder")
     * @Method({"POST"})
     */
    public function orderProjects()
    {
        // @todo переименовать метод и роут
        // @todo перенести сортировку проектов в админку
        $projects = $this->getRequest()->get('projects');
        $em = $this->get('doctrine')->getManager();
        foreach ($projects as $projectInfo) {
            $project = $em->getRepository("StfalconPortfolioBundle:Project")->find($projectInfo['id']);
            $project->setOrdernum($projectInfo['index']);
            $em->persist($project);
        }
        $em->flush();

        return new Response('good');
    }
}
