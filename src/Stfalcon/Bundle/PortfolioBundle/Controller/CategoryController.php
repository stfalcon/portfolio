<?php

namespace Stfalcon\Bundle\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * @return array
     *
     * @Route(
     *      "/services",
     *      name="portfolio_categories_list"
     * )
     * @Template()
     */
    public function servicesAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('StfalconPortfolioBundle:Category');
        $categories = $repository->getAllCategories();

        $linkTexts = array(
            'development' => 'веб-разработки',
            'design'      => 'дизайна',
            'mobile'      => 'мобильных приложений',
            'games'       => 'игор'
        );

        return array('categories' => $categories, 'linkTexts' => $linkTexts);
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
            ->getQueryForSelectProjectsByCategory($category);

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
        $em = $this->get('doctrine')->getEntityManager();
        foreach ($projects as $projectInfo) {
            $project = $em->getRepository("StfalconPortfolioBundle:Project")->find($projectInfo['id']);
            $project->setOrdernum($projectInfo['index']);
            $em->persist($project);
        }
        $em->flush();

        return new Response('good');
    }
}