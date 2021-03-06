<?php

namespace Application\Bundle\UserBundle\Controller;

use Application\Bundle\DefaultBundle\Helpers\SeoOpenGraphEnum;
use Application\Bundle\UserBundle\Entity\User;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Default controller.
 */
class DefaultController extends Controller
{
    /**
     * Team page.
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @Route("/team", name="team")
     */
    public function indexAction(Request $request)
    {
        $users = $this->getDoctrine()
            ->getRepository('ApplicationUserBundle:User')->findBy(
                [
                    'hideFromTeamSection' => false,
                    'locked' => false,
                ],
                [
                    'ordering' => 'ASC',
                ]
            );

        $interestsList = User::getInterestsList();

        $seo = $this->get('sonata.seo.page');
        $seo->addMeta('property', 'og:url', $this->generateUrl($request->get('_route'), [], true))
            ->addMeta('property', 'og:type', SeoOpenGraphEnum::WEBSITE);

        $repository = $this->getDoctrine()->getRepository('StfalconPortfolioBundle:Project');
        $projects = $repository->findAllProjectsOrderingByDate();

        $projectYears = [];
        $projectBefore = 1;
        /** @var Project $project */
        foreach ($projects as $project) {
            $year = (int) $project->getDate()->format('Y');
            if (isset($projectYears[$year]['counter'])) {
                ++$projectYears[$year]['counter'];
            } else {
                $projectYears[$year] = ['year' => $year, 'counter' => $projectBefore];
            }

            ++$projectBefore;
        }

        $projectYears = array_slice($projectYears, -4);

        return $this->render('@ApplicationUser/Default/index.html.twig', [
            'users' => $users,
            'interestsList' => $interestsList,
            'years' => $projectYears,
        ]);
    }
}
