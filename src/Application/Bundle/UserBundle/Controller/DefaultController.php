<?php

namespace Application\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Default controller. For single actions for project
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class DefaultController extends Controller
{

    /**
     * Categories/projects lilowerst
     *
     * @return array()
     * @Route("/", name="team")
     * @Template()
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getManager()
                ->getRepository("ApplicationUserBundle:User")->findBy(array('enabled' => true));

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

        return array('users' => $users, 'years' => $projectYears);
    }
}