<?php

namespace Stfalcon\Bundle\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Redirect controller
 */
class RedirectController extends Controller
{
    /**
     * @param string $categorySlug
     * @param string $projectSlug
     *
     * @return array
     * @Route("/portfolio/{categorySlug}/{projectSlug}",
     *  requirements={"projectSlug" = "uaroads|meinfernbus-de",
     *                "categorySlug" = "web-development|web-design"})
     */
    public function redirectOldLinksAction($categorySlug, $projectSlug)
    {
        switch (true) {
            case ($categorySlug == 'web-development' && $projectSlug == 'uaroads'):
                $redirectUrl = $this->generateUrl(
                    'portfolio_project_view',
                    [
                        'categorySlug' => $categorySlug,
                        'projectSlug'  => 'uaroads-com'
                    ]
                );
                break;
            case ($categorySlug == 'web-design' && $projectSlug == 'meinfernbus-de'):
                $redirectUrl = $this->generateUrl(
                    'portfolio_project_view',
                    [
                        'categorySlug' => 'web-development',
                        'projectSlug'  => $projectSlug
                    ]
                );
                break;
            default:
                return $this->forward('StfalconPortfolioBundle:Project:view', [
                    'categorySlug' => $categorySlug,
                    'projectSlug'  => $projectSlug
                ]);

        }

        return $this->redirect($redirectUrl, 301);
    }
}
