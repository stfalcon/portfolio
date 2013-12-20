<?php

namespace Application\Bundle\PortfolioBundle\Controller;

use Stfalcon\Bundle\PortfolioBundle\Controller\ProjectController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends BaseController
{
    /**
     * @param int $page
     *
     * @return array
     * @Route("/portfolio/{page}",
     *  name="portfolio_all_projects",
     *  defaults={"page"=1})
     * @Template("ApplicationPortfolioBundle:Default:all_projects.html.twig")
     */
    public function allProjectsAction($page)
    {
        $projects = $this->get('stfalcon_portfolio.project.repository')->findAll();
        $projectsWithPaginator = $this->get('knp_paginator')->paginate($projects, $page, 12);

        return array('projects' => $projectsWithPaginator);
    }
}
