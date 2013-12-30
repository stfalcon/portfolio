<?php

namespace Application\Bundle\UserBundle\Controller;

use Application\Bundle\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Default controller.
 */
class DefaultController extends Controller
{

    /**
     * Team page
     *
     * @return array()
     * @Route("/", name="team")
     * @Template()
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getManager()
                ->getRepository("ApplicationUserBundle:User")->findBy(array('enabled' => true));

        $interestsList = User::getInterestsList();

        return array('users' => $users, 'interestsList' => $interestsList);
    }

    /**
     * Projects counter widget
     *
     * @return array()
     * @Template("ApplicationUserBundle:Default:_projects_counter.html.twig")
     */
    public function projectsCounterAction()
    {
        $projects = $this->getDoctrine()->getManager()
            ->getRepository("StfalconPortfolioBundle:Project")->findBy(array(), array('date' => 'asc'));

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