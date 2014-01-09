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

    /**
     * Projects counter widget
     *
     * @return array()
     * @Template("ApplicationPortfolioBundle:Default:_projects_counter.html.twig")
     */
    public function projectsCounterAction()
    {
        $projects = $this->get('stfalcon_portfolio.project.repository')->findAllProjectsOrderingByDate();

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
}
